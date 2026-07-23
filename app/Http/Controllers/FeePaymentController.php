<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\StudentFee;
use Illuminate\Http\Request;

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

    public function create()
    {
        $studentFees = StudentFee::with('student')
            ->where('school_id', auth()->user()->school_id)
            ->where('status', '!=', 'paid')
            ->get();

        return view('school-admin.fee-payments.create', compact('studentFees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_fee_id' => ['required', 'exists:student_fees,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $studentFee = StudentFee::where('school_id', auth()->user()->school_id)
            ->findOrFail($validated['student_fee_id']);

        FeePayment::create([
            'student_fee_id' => $studentFee->id,
            'school_id' => auth()->user()->school_id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'] ?? null,
            'reference_no' => $validated['reference_no'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update the student fee's paid amount and status
        $newPaidAmount = $studentFee->paid_amount + $validated['amount'];
        $studentFee->paid_amount = $newPaidAmount;

        if ($newPaidAmount >= $studentFee->amount) {
            $studentFee->status = 'paid';
        } elseif ($newPaidAmount > 0) {
            $studentFee->status = 'partial';
        }

        $studentFee->save();

        return redirect()
            ->route('school-admin.fee-payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function destroy(FeePayment $feePayment)
    {
        abort_unless($feePayment->school_id === auth()->user()->school_id, 403);

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

        return redirect()
            ->route('school-admin.fee-payments.index')
            ->with('success', 'Payment removed successfully.');
    }
}