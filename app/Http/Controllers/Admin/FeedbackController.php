<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with(['user:id,name', 'school:id,name'])
            ->latest();

        // Optional filter: view a single school's feedback
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->query('school_id'));
        }

        if ($request->filled('role')) {
            $query->where('submitted_by_role', $request->query('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $feedbacks = $query->paginate(15)->withQueryString();

        $schools = School::select('id', 'name')->orderBy('name')->get();

        return view('admin.feedback.index', compact('feedbacks', 'schools'));
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