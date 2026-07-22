<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::with('schoolClass')->latest()->paginate(10);

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
            'class_id' => 'required|exists:classes,id',
        ]);

        Subject::create([
            'school_id' => auth()->user()->school_id,
            'class_id' => $validated['class_id'],
            'subject_name' => $validated['subject_name'],
            'subject_code' => $validated['subject_code'],
        ]);

        return redirect()->route('school-admin.subjects.index')
            ->with('status', 'Subject successfully added.');
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