<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * List all sent announcements, newest first.
     */
    public function index()
    {
        $announcements = Announcement::with('sender')
            ->latest()
            ->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form to compose a new announcement.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store (send) a new announcement with optional image.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], // 4MB
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('announcements', 'public');
        }

        $validated['sent_by'] = auth()->id();

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Announcement sent to all schools.');
    }

    /**
     * Delete an announcement (and its image).
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return back()->with('status', 'Announcement deleted.');
    }
}