<?php

namespace App\Http\Controllers;

use App\Models\BillingPeriod;
use App\Models\FeeDiscount;
use App\Models\FeeRate;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeeDiscountController extends Controller
{
    public function create(Request $request): View
    {
        $schoolId = auth()->user()->school_id;

        $classes = SchoolClass::orderBy('name')->get();
        $billingPeriods = BillingPeriod::where('is_active', true)->orderBy('hierarchy')->get();

        $sections = collect();
        $students = collect();
        $feeRows = collect();
        $selectedStudent = null;
        if ($request->filled('class_id')) {
            $class = SchoolClass::with('sections')
                ->where('id', $request->class_id)
                ->where('school_id', $schoolId)
                ->first();

            $sections = $class?->sections ?? collect();
        }
        if ($request->filled('class_id') && $request->filled('section_id')) {
            $students = Student::where('class_id', $request->class_id)
    ->where('section_id', $request->section_id)
    ->where('school_id', $schoolId)
    ->orderBy('first_name')
    ->orderBy('last_name')
    ->get();
        }

        if ($request->filled('student_id') && $request->filled('billing_period_id')) {
            $selectedStudent = Student::where('id', $request->student_id)
                ->where('school_id', $schoolId)
                ->first();

            if ($selectedStudent) {
                $rates = FeeRate::with('feeName')
                    ->where('class_id', $selectedStudent->class_id)
                    ->where('billing_period_id', $request->billing_period_id)
                    ->where('is_active', true)
                    ->get();

                // Check which of these fees have actually been assigned to
                // this student (i.e. a StudentFee row exists). Discounts can
                // only be meaningfully applied to fees that exist.
                $assignedFeeNameIds = StudentFee::where('student_id', $selectedStudent->id)
                    ->where('billing_period_id', $request->billing_period_id)
                    ->pluck('fee_name_id')
                    ->flip();

                $existingDiscounts = FeeDiscount::where('student_id', $selectedStudent->id)
                    ->where('billing_period_id', $request->billing_period_id)
                    ->get()
                    ->keyBy('fee_name_id');

                $feeRows = $rates->map(function ($rate) use ($existingDiscounts, $assignedFeeNameIds) {
                    $discount = $existingDiscounts->get($rate->fee_name_id);

                    $discountPercent = $discount->discount_percent ?? 0;
                    $discountAmount = $discount->discount_amount ?? 0;

                    $netAmount = $rate->amount - $discountAmount - ($rate->amount * $discountPercent / 100);
                    $netAmount = max(0, round($netAmount, 2));

                    return (object) [
                        'fee_name_id' => $rate->fee_name_id,
                        'fee_name' => $rate->feeName->name,
                        'amount_before' => $rate->amount,
                        'discount_percent' => $discountPercent,
                        'discount_amount' => $discountAmount,
                        'net_amount' => $netAmount,
                        'remarks' => $discount->remarks ?? '',
                        // True once the fee has actually been assigned to
                        // this student (a StudentFee row exists). If false,
                        // saving a discount here won't have any visible
                        // effect until the fee is assigned via Fee Assign.
                        'is_assigned' => $assignedFeeNameIds->has($rate->fee_name_id),
                    ];
                });
            }
        }

        return view('school-admin.fee-discounts.create', compact(
            'classes', 'sections', 'students', 'billingPeriods',
            'feeRows', 'selectedStudent'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'billing_period_id' => 'required|exists:billing_periods,id',
            'discounts' => 'required|array',
            'discounts.*.fee_name_id' => 'required|exists:fee_names,id',
            'discounts.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'discounts.*.discount_amount' => 'nullable|numeric|min:0',
            'discounts.*.remarks' => 'nullable|string|max:255',
        ]);

        $schoolId = auth()->user()->school_id;

        $student = Student::where('id', $validated['student_id'])
            ->where('school_id', $schoolId)
            ->first();

        if (!$student) {
            return redirect()->back()->withErrors(['student_id' => 'Invalid student.'])->withInput();
        }

        $unassignedFeeNames = [];
        $savedCount = 0;

        foreach ($validated['discounts'] as $row) {
            $percent = $row['discount_percent'] ?? 0;
            $amount = $row['discount_amount'] ?? 0;

            if ($percent == 0 && $amount == 0) {
                continue;
            }

            $studentFee = StudentFee::where('student_id', $validated['student_id'])
                ->where('fee_name_id', $row['fee_name_id'])
                ->where('billing_period_id', $validated['billing_period_id'])
                ->where('school_id', $schoolId)
                ->first();

            // Guard against the exact issue we hit: saving a discount for a
            // fee that was never actually assigned to this student. Instead
            // of silently doing nothing, record which fee names were
            // skipped so we can tell the user.
            if (!$studentFee) {
                $unassignedFeeNames[] = $row['fee_name_id'];
                continue;
            }

            FeeDiscount::updateOrCreate(
                [
                    'student_id' => $validated['student_id'],
                    'fee_name_id' => $row['fee_name_id'],
                    'billing_period_id' => $validated['billing_period_id'],
                ],
                [
                    'school_id' => $schoolId,
                    'discount_percent' => $percent,
                    'discount_amount' => $amount,
                    'remarks' => $row['remarks'] ?? null,
                ]
            );

            $rate = FeeRate::where('class_id', $student->class_id)
                ->where('billing_period_id', $validated['billing_period_id'])
                ->where('fee_name_id', $row['fee_name_id'])
                ->where('is_active', true)
                ->first();

            $originalAmount = $rate->amount ?? $studentFee->amount;

            $discountedAmount = $originalAmount - $amount - ($originalAmount * $percent / 100);
            $discountedAmount = max(0, round($discountedAmount, 2));

            $discountedAmount = max($discountedAmount, (float) $studentFee->paid_amount);

            $studentFee->amount = $discountedAmount;
            $studentFee->status = $studentFee->paid_amount >= $discountedAmount
                ? 'paid'
                : ($studentFee->paid_amount > 0 ? 'partial' : 'unpaid');
            $studentFee->save();

            $savedCount++;
        }

        if (!empty($unassignedFeeNames)) {
            $names = \App\Models\FeeName::whereIn('id', $unassignedFeeNames)->pluck('name')->implode(', ');

            return redirect()->back()->withInput()->withErrors([
                'discounts' => "These fees haven't been assigned to this student yet, so the discount couldn't be saved: {$names}. Assign the fee first via Fee Assign, then apply the discount.",
            ]);
        }

        // Redirect back with only class/section preserved so the fee table
        // (billing period + student) clears after a successful save.
        return redirect()->route('school-admin.fee-discounts.create', [
            'class_id' => request('class_id'),
            'section_id' => request('section_id'),
        ])->with('status', "Discounts saved successfully ({$savedCount} fee(s) updated).");
    }
}