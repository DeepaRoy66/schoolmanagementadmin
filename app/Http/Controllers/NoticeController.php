<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function index(): View
    {
        $notices = Notice::latest()->paginate(10);

        return view('school-admin.notices.index', compact('notices'));
    }

    public function create(): View
    {
        return view('school-admin.notices.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notice::create([
            'school_id' => auth()->user()->school_id,
            'posted_by' => auth()->id(),
            'title' => $validated['title'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('school-admin.notices.index')
            ->with('status', 'Notice posted successfully.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('school-admin.notices.index')
            ->with('status', 'Notice deleted.');
    }
}