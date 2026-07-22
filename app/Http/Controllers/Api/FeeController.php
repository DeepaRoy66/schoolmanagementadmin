<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeeController extends Controller
{
    public function myFees(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $fees = Fee::where('student_id', $student->id)
            ->get(['id', 'title', 'amount', 'paid_amount', 'status', 'due_date']);

        return response()->json($fees);
    }

    public function summary(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $fees = Fee::where('student_id', $student->id)->get();

        $total = $fees->sum('amount');
        $paid = $fees->sum('paid_amount');
        $remaining = $total - $paid;

        $categories = $fees->map(function ($fee) {
            return [
                'title' => $fee->title,
                'amount' => $fee->amount,
                'paid_amount' => $fee->paid_amount,
                'status' => $fee->status,
            ];
        });

        return response()->json([
            'total' => $total,
            'paid' => $paid,
            'remaining' => $remaining,
            'categories' => $categories,
        ]);
    }
}