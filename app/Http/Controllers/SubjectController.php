<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $subjects = Subject::query()
            ->with('schoolClass')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('subject_name', 'like', '%' . $request->search . '%')
                      ->orWhere('subject_code', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('school-admin.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('school-admin.subjects.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50',
            'class_ids' => 'required|array|min:1',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $schoolId = auth()->user()->school_id;

        foreach ($validated['class_ids'] as $classId) {
            Subject::firstOrCreate(
                [
                    'class_id' => $classId,
                    'subject_code' => $validated['subject_code'],
                ],
                [
                    'school_id' => $schoolId,
                    'subject_name' => $validated['subject_name'],
                ]
            );
        }

        $count = count($validated['class_ids']);

        return redirect()->route('school-admin.subjects.index')
            ->with('status', $count > 1
                ? "Subject successfully added to {$count} classes."
                : 'Subject successfully added.');
    }

    public function edit(Subject $subject): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('school-admin.subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50',
            'class_id' => 'required|exists:classes,id',
        ]);

        $subject->update($validated);

        return redirect()->route('school-admin.subjects.index')
            ->with('status', 'Subject successfully updated.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('school-admin.subjects.index')
            ->with('status', 'Subject deleted.');
    }
}