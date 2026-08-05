<?php

namespace App\Http\Controllers;

use App\Models\BillingPeriod;
use App\Models\FeeDiscount;
use App\Models\FeeRate;
use App\Models\Invoice;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FeeAssignController extends Controller
{
    public function index(Request $request): View
    {
        $schoolId = auth()->user()->school_id;

        $invoices = Invoice::where('school_id', $schoolId)
            ->with(['student', 'student.schoolClass', 'student.section', 'billingPeriod', 'creator', 'studentFees'])
            ->orderByDesc('billing_date')
            ->get();

        $assignedFees = $invoices->map(function (Invoice $invoice) {
            return (object) [
                'id' => $invoice->id,
                'fee_ids' => $invoice->studentFees->pluck('id')->values(),
                'student' => $invoice->student,
                'class' => $invoice->student->schoolClass ?? null,
                'section' => $invoice->student->section ?? null,
                'invoice_no' => $invoice->invoice_no,
                'fiscal_year' => optional($invoice->billingPeriod)->fiscal_year
                    ?? \Carbon\Carbon::parse($invoice->billing_date)->format('Y'),
                'bill_amount' => $invoice->total_amount,
                'billingPeriod' => $invoice->billingPeriod,
                'bill_date' => $invoice->billing_date,
                'due_date' => $invoice->due_date,
                'active' => $invoice->status !== 'void',
                'created_by_name' => optional($invoice->creator)->name ?? 'admin',
            ];
        })->values();

        return view('school-admin.fee-assign.index', compact('assignedFees'));
    }

    public function create(Request $request): View
    {
        $schoolId = auth()->user()->school_id;

        
        $classes = SchoolClass::where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $billingPeriods = BillingPeriod::where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('hierarchy')
            ->get();

        $sections = collect();
        $students = collect();
        $feeRates = collect();

        if ($request->filled('class_id')) {
            $sections = Section::where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
        }

        if ($request->filled('class_id') && $request->boolean('is_individual')) {
            $students = Student::where('school_id', $schoolId)
                ->where('class_id', $request->class_id)
                ->when($request->filled('section_id'), fn($q) => $q->where('section_id', $request->section_id))
                ->orderBy('first_name')
                ->get();
        }


        if ($request->filled('class_id') && $request->filled('billing_period_id')) {
            $feeRates = FeeRate::where('school_id', $schoolId)
                ->where('class_id', $request->class_id)
                ->where('billing_period_id', $request->billing_period_id)
                ->where('is_active', true)
                ->with('feeName') 
                ->orderBy('id')
                ->get();
        }

        return view('school-admin.fee-assign.create', compact(
            'classes',
            'billingPeriods',
            'sections',
            'students',
            'feeRates'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'is_individual' => 'nullable|boolean',
            'student_id' => 'required_if:is_individual,1|nullable|exists:students,id',
            'billing_period_id' => 'required|exists:billing_periods,id',
            'fee_rate_ids' => 'required|array|min:1',
            'fee_rate_ids.*' => 'exists:fee_rates,id',
            'billing_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:billing_date',
            'narration' => 'nullable|string|max:500',
        ], [
            'fee_rate_ids.required' => 'Please select at least one fee to assign.',
        ]);

        $schoolId = auth()->user()->school_id;

        
        $ownClass = SchoolClass::where('id', $validated['class_id'])->where('school_id', $schoolId)->exists();
        $ownPeriod = BillingPeriod::where('id', $validated['billing_period_id'])->where('school_id', $schoolId)->exists();

        if (!$ownClass || !$ownPeriod) {
            return redirect()->back()->withErrors(['class_id' => 'Invalid selection.'])->withInput();
        }

        if (!empty($validated['section_id'])) {
            $ownSection = Section::where('id', $validated['section_id'])
                ->where('school_id', $schoolId)
                ->exists();

            if (!$ownSection) {
                return redirect()->back()->withErrors(['section_id' => 'Invalid section.'])->withInput();
            }
        }

        if ($request->boolean('is_individual')) {
            $ownStudent = Student::where('id', $validated['student_id'])->where('school_id', $schoolId)->exists();
            if (!$ownStudent) {
                return redirect()->back()->withErrors(['student_id' => 'Invalid student.'])->withInput();
            }
            $studentIds = collect([$validated['student_id']]);
        } else {
            $studentIds = Student::where('school_id', $schoolId)
                ->where('class_id', $validated['class_id'])
                ->when(!empty($validated['section_id']), fn($q) => $q->where('section_id', $validated['section_id']))
                ->pluck('id');
        }

        if ($studentIds->isEmpty()) {
            return redirect()->back()->withErrors(['class_id' => 'No students found for this selection.'])->withInput();
        }

        $feeRates = FeeRate::where('school_id', $schoolId)
            ->where('class_id', $validated['class_id'])
            ->where('billing_period_id', $validated['billing_period_id'])
            ->where('is_active', true)
            ->whereIn('id', $validated['fee_rate_ids'])
            ->get();

        if ($feeRates->isEmpty()) {
            return redirect()->back()
                ->withErrors(['fee_rate_ids' => 'Selected Fee Rates are invalid or no longer available.'])
                ->withInput();
        }

        $assignedCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($studentIds, $feeRates, $validated, $schoolId, &$assignedCount, &$skippedCount) {
            foreach ($studentIds as $studentId) {
                $discounts = FeeDiscount::where('student_id', $studentId)
                    ->where('billing_period_id', $validated['billing_period_id'])
                    ->get()
                    ->keyBy('fee_name_id');

                $ratesToAssign = [];
                foreach ($feeRates as $rate) {
                    $alreadyExists = StudentFee::where('student_id', $studentId)
                        ->where('fee_name_id', $rate->fee_name_id)
                        ->where('billing_period_id', $validated['billing_period_id'])
                        ->exists();

                    if ($alreadyExists) {
                        $skippedCount++;
                        continue; 
                    }

                    $ratesToAssign[] = $rate;
                }

                if (empty($ratesToAssign)) {
                    continue; 
                }

                
                $invoice = Invoice::create([
                    'school_id' => $schoolId,
                    'student_id' => $studentId,
                    'billing_period_id' => $validated['billing_period_id'],
                    'invoice_no' => 'TEMP', 
                    'total_amount' => 0,
                    'billing_date' => $validated['billing_date'],
                    'due_date' => $validated['due_date'],
                    'status' => 'unpaid',
                    'notes' => $validated['narration'] ?? null,
                    'created_by' => auth()->id(),
                ]);

              
                $invoice->invoice_no = 'INV-' . now()->format('Y') . '-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT);

             
                $total = 0;
                foreach ($ratesToAssign as $rate) {
                    $amount = $rate->amount;

                  
                    $discount = $discounts->get($rate->fee_name_id);
                    if ($discount) {
                        $amount -= ($discount->discount_amount ?? 0);
                        $amount -= $rate->amount * (($discount->discount_percent ?? 0) / 100);
                        $amount = max(0, round($amount, 2));
                    }

                    StudentFee::create([
                        'school_id' => $schoolId,
                        'student_id' => $studentId,
                        'fee_name_id' => $rate->fee_name_id,
                        'billing_period_id' => $validated['billing_period_id'],
                        'amount' => $amount,
                        'status' => 'unpaid',
                        'billing_date' => $validated['billing_date'],
                        'due_date' => $validated['due_date'],
                        'notes' => $validated['narration'] ?? null,
                        'created_by' => auth()->id(),
                        'invoice_id' => $invoice->id,
                    ]);

                    $total += $amount;
                    $assignedCount++;
                }

                $invoice->total_amount = $total;
                $invoice->save();
            }
        });

        $message = "Fee assigned successfully. {$assignedCount} fee record(s) created.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} record(s) skipped (already assigned).";
        }

        return redirect()->route('school-admin.fee-assign.index')
            ->with('status', $message);
    }

    public function bulkVoid(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|min:1',
        ]);

        $schoolId = auth()->user()->school_id;
        $flatIds = collect($request->input('ids'))
            ->flatMap(fn ($group) => explode(',', $group))
            ->filter()
            ->unique()
            ->values();

        DB::transaction(function () use ($schoolId, $flatIds) {
            $fees = StudentFee::where('school_id', $schoolId)
                ->whereIn('id', $flatIds)
                ->get();

            $fees->each->update(['status' => 'void']);

            $invoiceIds = $fees->pluck('invoice_id')->filter()->unique();

            Invoice::where('school_id', $schoolId)
                ->whereIn('id', $invoiceIds)
                ->update(['status' => 'void']);
        });

        return response()->json(['status' => 'ok']);
    }

    public function invoice(Request $request): View
    {
        $schoolId = auth()->user()->school_id;

        $flatIds = collect(explode(',', $request->query('ids', '')))
            ->filter()
            ->unique()
            ->values();

        $fees = StudentFee::where('school_id', $schoolId)
            ->whereIn('id', $flatIds)
            ->with(['student', 'feeName', 'billingPeriod', 'school', 'invoice'])
            ->get()
            ->groupBy(fn ($fee) => $fee->invoice_id
                ?? ('legacy|' . $fee->student_id . '|' . $fee->billing_period_id . '|' . $fee->billing_date));

        return view('school-admin.fee-assign.invoice', compact('fees'));
    }
}