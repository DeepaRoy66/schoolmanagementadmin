<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Feedback::with('user:id,name')
            ->where('school_id', $user->school_id)
            ->latest();

        if ($request->filled('role')) {
            $query->where('submitted_by_role', $request->query('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $feedbacks = $query->paginate(15)->withQueryString();

        return view('school-admin.feedbacks.index', compact('feedbacks'));
    }

    public function updateStatus(Request $request, Feedback $feedback): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
        ]);

        $feedback->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return back()->with('success', 'Feedback deleted.');
    }
}