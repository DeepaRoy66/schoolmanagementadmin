<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeeController extends Controller
{
    /**
     * Student: aafno fee records herne
     */
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
            ->orderBy('due_date')
            ->get(['title', 'amount', 'paid_amount', 'status', 'due_date', 'paid_date']);

        return response()->json($fees);
    }
}