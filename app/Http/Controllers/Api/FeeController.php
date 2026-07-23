<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
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
            'summary' => [
                'total' => round($total, 2),
                'paid' => round($paid, 2),
                'remaining' => round($remaining, 2),
            ],
            'categories' => $categories,
        ]);
    }
}