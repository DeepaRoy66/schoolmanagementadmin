<?php

namespace App\Http\Controllers;

use App\Models\BillingPeriod;
use App\Models\FeeDiscount;
use App\Models\FeeRate;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
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

        // Class select vayo bhane, tyo class ko sections dekhaune
        if ($request->filled('class_id')) {
            $class = SchoolClass::with('sections')
                ->where('id', $request->class_id)
                ->where('school_id', $schoolId)
                ->first();

            $sections = $class?->sections ?? collect();
        }

        // Section select vayo bhane, students dekhaune
        if ($request->filled('class_id') && $request->filled('section_id')) {
            $students = Student::where('class_id', $request->class_id)
    ->where('section_id', $request->section_id)
    ->where('school_id', $schoolId)
    ->orderBy('first_name')
    ->orderBy('last_name')
    ->get();
        }

        // Student + billing period select vayo bhane, fee table taiyar garne
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

                $existingDiscounts = FeeDiscount::where('student_id', $selectedStudent->id)
                    ->where('billing_period_id', $request->billing_period_id)
                    ->get()
                    ->keyBy('fee_name_id');

                $feeRows = $rates->map(function ($rate) use ($existingDiscounts) {
                    $discount = $existingDiscounts->get($rate->fee_name_id);

                    return (object) [
                        'fee_name_id' => $rate->fee_name_id,
                        'fee_name' => $rate->feeName->name,
                        'amount_before' => $rate->amount,
                        'discount_percent' => $discount->discount_percent ?? 0,
                        'discount_amount' => $discount->discount_amount ?? 0,
                        'remarks' => $discount->remarks ?? '',
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

        // SECURITY: student aafnai school ko ho ki confirm garne
        $ownStudent = Student::where('id', $validated['student_id'])
            ->where('school_id', $schoolId)
            ->exists();

        if (!$ownStudent) {
            return redirect()->back()->withErrors(['student_id' => 'Invalid student.'])->withInput();
        }

        foreach ($validated['discounts'] as $row) {
            $percent = $row['discount_percent'] ?? 0;
            $amount = $row['discount_amount'] ?? 0;

            
            if ($percent == 0 && $amount == 0) {
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
        }

        return redirect()->route('school-admin.fee-discounts.create', [
            'class_id' => request('class_id'),
            'section_id' => request('section_id'),
            'billing_period_id' => $validated['billing_period_id'],
            'student_id' => $validated['student_id'],
        ])->with('status', 'Discounts saved successfully.');
    }
}