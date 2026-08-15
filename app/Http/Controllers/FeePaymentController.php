<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FeePaymentController extends Controller
{
    public function index()
    {
        $payments = FeePayment::with(['studentFee.student'])
            ->where('school_id', auth()->user()->school_id)
            ->orderByDesc('payment_date')
            ->paginate(20);

        return view('school-admin.fee-payments.index', compact('payments'));
    }
    public function create(Request $request): View
    {
        $schoolId = auth()->user()->school_id;

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();

        $students = collect();

        if ($request->filled('class_id') || $request->filled('section_id')
            || $request->filled('student_name') || $request->filled('student_number')) {


            $students = Student::where('students.school_id', $schoolId)
                ->when($request->filled('class_id'), fn ($q) => $q->where('class_id', $request->class_id))
                ->when($request->filled('section_id'), fn ($q) => $q->where('section_id', $request->section_id))
                ->when($request->filled('student_name'), function ($q) use ($request) {
                    $name = $request->student_name;
                    $q->where(function ($qq) use ($name) {
                        $qq->where('first_name', 'like', "%{$name}%")
                           ->orWhere('last_name', 'like', "%{$name}%");
                    });
                })
                ->when($request->filled('student_number'), fn ($q) => $q->where('roll_number', 'like', "%{$request->student_number}%"))
                ->select('students.*')
                ->selectSub(function ($q) {
                    $q->from('student_fees')
                        ->selectRaw('COALESCE(SUM(amount), 0)')
                        ->whereColumn('student_fees.student_id', 'students.id');
                }, 'total_fee_amount')
                ->selectSub(function ($q) {
                    $q->from('student_fees')
                        ->selectRaw('COALESCE(SUM(paid_amount), 0)')
                        ->whereColumn('student_fees.student_id', 'students.id');
                }, 'total_paid_amount')
                ->with(['schoolClass', 'section'])
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->paginate(15)
                ->withQueryString();
        }

        return view('school-admin.fee-payments.create', compact('classes', 'sections', 'students'));
    }

    public function payFeeForm(Student $student): View
    {
        abort_unless($student->school_id === auth()->user()->school_id, 403);

        $student->load(['schoolClass', 'section']);
        $studentFees = StudentFee::with('feeName')
            ->where('student_id', $student->id)
            ->orderBy('id')
            ->get();

        $netPayable = $studentFees->sum(fn ($fee) => $fee->amount - $fee->paid_amount);

        // Fixed list of payment types. payment_method is stored as a plain
        // string on FeePayment, so these are simple value => label pairs
        // rather than a related model.
        $paymentTypes = [
            'cash' => 'Cash',
            'card' => 'Card',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'online' => 'Online',
        ];

        // TODO: replace these placeholders with real data once
        // Sponsor / Project models exist.
        $sponsors = collect();
        $projects = collect();
        $dateFrom = null;
        $paymentDate = now()->format('Y-m-d');

        // Build the transaction ledger: every fee charge is a Dr entry,
        // every payment against it is a Cr entry. Sorted chronologically
        // with a running balance.
        $payments = FeePayment::with('studentFee')
            ->whereIn('student_fee_id', $studentFees->pluck('id'))
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        $entries = collect();

        foreach ($studentFees as $fee) {
            $entries->push((object) [
                'date' => optional($fee->billing_date)->format('Y-m-d') ?? optional($fee->created_at)->format('Y-m-d'),
                'sort_key' => $fee->billing_date ?? $fee->created_at,
                'transaction_type' => 'Fee Charge: ' . ($fee->feeName->name ?? '-'),
                'transaction_no' => null,
                'transaction_url' => null,
                'period' => $fee->billingPeriod->name ?? '-',
                'remarks' => $fee->notes,
                'dr_amount' => $fee->amount,
                'fine' => 0,
                'cr_amount' => 0,
                'fine_waive' => 0,
                'rebate' => 0,
            ]);
        }

        foreach ($payments as $payment) {
            $entries->push((object) [
                'date' => optional($payment->payment_date)->format('Y-m-d') ?? $payment->payment_date,
                'sort_key' => $payment->payment_date ?? $payment->created_at,
                'transaction_type' => 'Payment',
                'transaction_no' => $payment->reference_no ?? ('#' . $payment->id),
                'transaction_url' => route('school-admin.fee-payments.receipt', $payment->payment_group),
                'period' => $payment->studentFee->billingPeriod->name ?? '-',
                'remarks' => $payment->notes,
                'dr_amount' => 0,
                'fine' => 0,
                'cr_amount' => $payment->amount,
                'fine_waive' => 0,
                'rebate' => 0,
            ]);
        }

        $entries = $entries->sortBy('sort_key')->values();

        $runningBalance = 0;
        $transactions = $entries->map(function ($entry) use (&$runningBalance) {
            $runningBalance += $entry->dr_amount - $entry->cr_amount;
            $entry->balance = $runningBalance;
            $entry->balance_type = $runningBalance >= 0 ? 'Dr' : 'Cr';
            return $entry;
        });

        $netPayableType = $netPayable >= 0 ? 'Dr' : 'Cr';

        return view('school-admin.fee-payments.pay', compact(
            'student',
            'studentFees',
            'netPayable',
            'paymentTypes',
            'sponsors',
            'projects',
            'transactions',
            'netPayableType',
            'dateFrom',
            'paymentDate'
        ));
    }

    /**
     * Handle submission of the payment form on the pay.blade.php page.
     *
     * FIXED: previously this ignored the submitted `payment_date` and
     * always stored now()->format('Y-m-d'), so whatever date the user
     * picked on the form was silently discarded. `payment_date` is now
     * validated and actually used when creating each FeePayment row.
     */
    public function payStore(Request $request, Student $student): RedirectResponse
    {
        abort_unless($student->school_id === auth()->user()->school_id, 403);

        $validated = $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_type' => ['required', 'string', 'max:50'],
            'payment_date' => ['required', 'date_format:Y-m-d'],
            'apply_discount' => ['nullable', 'boolean'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'narration' => ['nullable', 'string', 'max:1000'],
        ]);

        $pendingFees = StudentFee::where('student_id', $student->id)
            ->where('status', '!=', 'paid')
            ->orderBy('id')
            ->get();

        $netPayable = $pendingFees->sum(fn ($fee) => $fee->amount - $fee->paid_amount);

        if ($validated['payment_amount'] > $netPayable) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment_amount' => 'Payment amount cannot exceed the net payable of Rs. '
                        . number_format($netPayable, 2) . '.',
                ]);
        }

        $paymentGroup = (string) \Illuminate\Support\Str::uuid();

        DB::transaction(function () use ($pendingFees, $validated, $paymentGroup) {
            $remaining = $validated['payment_amount'];

            foreach ($pendingFees as $fee) {
                if ($remaining <= 0) {
                    break;
                }

                $due = $fee->amount - $fee->paid_amount;
                $allocate = min($due, $remaining);

                if ($allocate <= 0) {
                    continue;
                }

                FeePayment::create([
                    'student_fee_id' => $fee->id,
                    'payment_group' => $paymentGroup,
                    'school_id' => auth()->user()->school_id,
                    'amount' => $allocate,
                    'payment_date' => $validated['payment_date'],
                    'payment_method' => $validated['payment_type'],
                    'notes' => $validated['narration'] ?? null,
                ]);

                $fee->paid_amount += $allocate;
                $fee->status = $fee->paid_amount >= $fee->amount ? 'paid' : 'partial';
                $fee->save();

                $remaining -= $allocate;
            }
        });

        return redirect()
            ->route('school-admin.fee-payments.receipt', $paymentGroup)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Fee Statement page for a student.
     * STUB: replace with real statement logic once requirements are known.
     */
    public function statement(Student $student): View
    {
        abort_unless($student->school_id === auth()->user()->school_id, 403);

        $studentFees = StudentFee::with('feeName')
            ->where('student_id', $student->id)
            ->orderBy('id')
            ->get();

        return view('school-admin.fee-payments.statement', compact('student', 'studentFees'));
    }

    /**
     * Fine Waive form for a student.
     * STUB: replace with real fine/waiver logic once requirements are known.
     */
    public function fineWaiveForm(Student $student): View
    {
        abort_unless($student->school_id === auth()->user()->school_id, 403);

        return view('school-admin.fee-payments.fine-waive', compact('student'));
    }

    public function receipt(string $paymentGroup): View
    {
        $schoolId = auth()->user()->school_id;

        $payments = FeePayment::with('studentFee.student.schoolClass', 'studentFee.student.section')
            ->where('payment_group', $paymentGroup)
            ->where('school_id', $schoolId)
            ->orderBy('id')
            ->get();

        abort_if($payments->isEmpty(), 404);

        $student = $payments->first()->studentFee->student;
        $paidAmount = $payments->sum('amount');
        $paymentDate = $payments->first()->payment_date;
        $remainingBalance = StudentFee::where('student_id', $student->id)
            ->get()
            ->sum(fn ($fee) => $fee->amount - $fee->paid_amount);

        $preBalance = $remainingBalance + $paidAmount;

        return view('school-admin.fee-payments.receipt', [
            'student' => $student,
            'paidAmount' => $paidAmount,
            'paymentDate' => $paymentDate,
            'preBalance' => $preBalance,
            'remainingBalance' => $remainingBalance,
            'amountInWords' => $this->numberToWords((int) round($paidAmount)),
            'receiptNo' => strtoupper(substr($paymentGroup, 0, 8)),
        ]);
    }
    private function numberToWords(int $number): string
    {
        if ($number === 0) {
            return 'Zero';
        }

        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
                 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
                 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $toWords = function (int $n) use (&$toWords, $ones, $tens): string {
            if ($n < 20) return $ones[$n];
            if ($n < 100) return trim($tens[intdiv($n, 10)] . ' ' . $ones[$n % 10]);
            if ($n < 1000) return trim($ones[intdiv($n, 100)] . ' Hundred ' . $toWords($n % 100));
            if ($n < 100000) return trim($toWords(intdiv($n, 1000)) . ' Thousand ' . $toWords($n % 1000));
            if ($n < 10000000) return trim($toWords(intdiv($n, 100000)) . ' Lakh ' . $toWords($n % 100000));
            return trim($toWords(intdiv($n, 10000000)) . ' Crore ' . $toWords($n % 10000000));
        };

        return $toWords($number);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $student = Student::findOrFail($validated['student_id']);

        $pendingFees = StudentFee::where('student_id', $student->id)
            ->where('status', '!=', 'paid')
            ->orderBy('id')
            ->get();

        $netPayable = $pendingFees->sum(fn ($fee) => $fee->amount - $fee->paid_amount);

        if ($validated['amount'] > $netPayable) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' => 'Payment amount cannot exceed the net payable of Rs. '
                        . number_format($netPayable, 2) . '.',
                ]);
        }

        $paymentGroup = (string) \Illuminate\Support\Str::uuid();

        DB::transaction(function () use ($pendingFees, $validated, $paymentGroup) {
            $remaining = $validated['amount'];

            foreach ($pendingFees as $fee) {
                if ($remaining <= 0) {
                    break;
                }

                $due = $fee->amount - $fee->paid_amount;
                $allocate = min($due, $remaining);

                if ($allocate <= 0) {
                    continue;
                }

                FeePayment::create([
                    'student_fee_id' => $fee->id,
                    'payment_group' => $paymentGroup,
                    'school_id' => auth()->user()->school_id,
                    'amount' => $allocate,
                    'payment_date' => $validated['payment_date'],
                    'payment_method' => $validated['payment_method'] ?? null,
                    'reference_no' => $validated['reference_no'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                $fee->paid_amount += $allocate;
                $fee->status = $fee->paid_amount >= $fee->amount ? 'paid' : 'partial';
                $fee->save();

                $remaining -= $allocate;
            }
        });

        return redirect()
            ->route('school-admin.fee-payments.pay-form', $validated['student_id'])
            ->with('success', 'Payment recorded successfully.')
            ->with('payment_group', $paymentGroup);
    }

    public function destroy(FeePayment $feePayment): RedirectResponse
    {
        abort_unless($feePayment->school_id === auth()->user()->school_id, 403);

        DB::transaction(function () use ($feePayment) {
            $studentFee = $feePayment->studentFee;

            $feePayment->delete();

            // Recalculate paid amount and status after deletion
            $newPaidAmount = $studentFee->feePayments()->sum('amount');
            $studentFee->paid_amount = $newPaidAmount;

            if ($newPaidAmount >= $studentFee->amount) {
                $studentFee->status = 'paid';
            } elseif ($newPaidAmount > 0) {
                $studentFee->status = 'partial';
            } else {
                $studentFee->status = 'unpaid';
            }

            $studentFee->save();
        });

        return redirect()
            ->route('school-admin.fee-payments.index')
            ->with('success', 'Payment removed successfully.');
    }
}