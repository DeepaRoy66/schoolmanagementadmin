<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeedbackController extends Controller
{
    /**
     * Student/Teacher: submit feedback.
     */
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

    /**
     * Student/Teacher: view their own submitted feedback.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $feedbacks = Feedback::where('school_id', $user->school_id)
            ->where('submitted_by', $user->id)
            ->latest()
            ->paginate(20);

        return response()->json($feedbacks);
    }

    /**
     * Super Admin only: view feedback across all schools.
     * Optional filters: ?role=student|teacher  &status=pending|reviewed|resolved
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'super_admin') {
            return response()->json(['message' => 'You are not authorized to view this.'], 403);
        }

        $query = Feedback::query();

        if ($request->filled('role')) {
            $query->where('submitted_by_role', $request->query('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $feedbacks = $query->latest()->paginate(20);

        return response()->json($feedbacks);
    }

    /**
     * Super Admin only: view a single feedback in detail.
     */
    public function adminShow(Request $request, Feedback $feedback): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'super_admin') {
            return response()->json(['message' => 'You are not authorized to view this.'], 403);
        }

        return response()->json($feedback);
    }

    /**
     * Super Admin only: update feedback status.
     */
    public function updateStatus(Request $request, Feedback $feedback): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'super_admin') {
            return response()->json(['message' => 'You are not authorized to update this.'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
        ]);

        $feedback->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Feedback status updated.',
            'feedback' => $feedback,
        ]);
    }
}