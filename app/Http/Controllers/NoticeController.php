<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\SchoolClass;
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
        $classes = SchoolClass::with('sections')
            ->where('school_id', auth()->user()->school_id)
            ->get();

        return view('school-admin.notices.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_type' => 'required|in:all,teacher,student,class_specific',
            'targets' => 'required_if:target_type,class_specific|array',
            'targets.*.class_id' => 'required_with:targets|exists:classes,id',
            'targets.*.section_id' => 'nullable|exists:sections,id',
        ]);

        $notice = Notice::create([
            'school_id' => auth()->user()->school_id,
            'posted_by' => auth()->id(),
            'title' => $validated['title'],
            'message' => $validated['message'],
            'target_type' => $validated['target_type'],
        ]);

        if ($validated['target_type'] === 'class_specific' && !empty($validated['targets'])) {
            foreach ($validated['targets'] as $target) {
                $notice->targets()->create([
                    'class_id' => $target['class_id'],
                    'section_id' => $target['section_id'] ?? null,
                ]);
            }
        }

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