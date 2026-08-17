<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
use App\Models\FeePayment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeeController extends Controller
{
    private function getStudentOrFail(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            abort(response()->json(['message' => 'Only students can access this.'], 403));
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            abort(response()->json(['message' => 'Student profile not found.'], 404));
        }

        return $student;
    }

    public function myFees(Request $request): JsonResponse
    {
        $student = $this->getStudentOrFail($request);
        $student->load('schoolClass:id,name');

        $fees = StudentFee::with('feeCategory')
            ->where('student_id', $student->id)
            ->get();

        $total = $fees->sum('amount');
        $paid = $fees->sum('paid_amount');
        $remaining = $total - $paid;

        $categories = $fees->map(function ($fee) {
            return [
                'id' => $fee->id,
                'category' => $fee->feeCategory->name ?? 'Uncategorized',
                'amount' => $fee->amount,
                'paid_amount' => $fee->paid_amount,
                'remaining' => $fee->amount - $fee->paid_amount,
                'status' => $fee->status,
                'due_date' => $fee->due_date,
            ];
        });

        return response()->json([
            'class_id' => $student->class_id,
            'class_name' => $student->schoolClass?->name,
            'summary' => [
                'total' => round($total, 2),
                'paid' => round($paid, 2),
                'remaining' => round($remaining, 2),
            ],
            'categories' => $categories,
        ]);
    }

    /**
     * GET /api/student/payment-history
     * Logged-in student ko fee payment history - filter + pagination sahit.
     */
    public function myPaymentHistory(Request $request): JsonResponse
    {
        $student = $this->getStudentOrFail($request);

        $validated = $request->validate([
            'payment_method' => ['nullable', 'string'],
            'from_date'       => ['nullable', 'date'],
            'to_date'         => ['nullable', 'date', 'after_or_equal:from_date'],
            'per_page'        => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = FeePayment::query()
            ->whereHas('studentFee', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with(['studentFee.feeName', 'studentFee.feeCategory', 'studentFee.billingPeriod']);

        if (!empty($validated['payment_method'])) {
            $query->where('payment_method', $validated['payment_method']);
        }

        if (!empty($validated['from_date'])) {
            $query->whereDate('payment_date', '>=', $validated['from_date']);
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('payment_date', '<=', $validated['to_date']);
        }

        $perPage = $validated['per_page'] ?? 15;

        $payments = $query->orderByDesc('payment_date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $payments->getCollection()->map(function ($payment) {
                return [
                    'id'              => $payment->id,
                    'amount'          => (float) $payment->amount,
                    'payment_date'    => \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d'),
                    'payment_method'  => $payment->payment_method,
                    'reference_no'    => $payment->reference_no,
                    'payment_group'   => $payment->payment_group,
                    'notes'           => $payment->notes,
                    'fee_name'        => $payment->studentFee->feeName->name ?? null,
                    'fee_category'    => $payment->studentFee->feeCategory->name ?? null,
                    'billing_period'  => $payment->studentFee->billingPeriod->name ?? null,
                    'student_fee_id'  => $payment->student_fee_id,
                    'created_at'      => $payment->created_at->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page'    => $payments->lastPage(),
                'per_page'     => $payments->perPage(),
                'total'        => $payments->total(),
            ],
        ]);
    }
}