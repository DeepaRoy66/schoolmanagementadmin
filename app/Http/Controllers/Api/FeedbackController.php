<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!in_array($user->role, ['student', 'teacher'])) {
            return response()->json(['message' => 'You are not authorized to submit feedback.'], 403);
        }

        $validated = $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $feedback = Feedback::create([
            'school_id' => $user->school_id,
            'submitted_by' => $user->id,
            'submitted_by_role' => $user->role,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Feedback submitted successfully.',
            'feedback' => $feedback,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $feedbacks = Feedback::where('school_id', $user->school_id)
            ->where('submitted_by', $user->id)
            ->latest()
            ->paginate(20);

        return response()->json($feedbacks);
    }
}