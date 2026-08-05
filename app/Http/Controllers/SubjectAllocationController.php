<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectAllocation;
use Illuminate\Http\Request;

class SubjectAllocationController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('schoolClass.sections')->orderBy('subject_name')->get();
        $teachers = Teacher::where('is_active', true)->orderBy('first_name')->get();

        $allocations = TeacherSubjectAllocation::with('teacher')
            ->get()
            ->keyBy(fn ($a) => $a->subject_id . '-' . $a->section_id);

        $rows = [];
        foreach ($subjects as $subject) {
            $sections = $subject->schoolClass?->sections ?? collect();

            foreach ($sections as $section) {
                $key = $subject->id . '-' . $section->id;
                $existing = $allocations->get($key);

                $rows[] = [
                    'class_name'   => $subject->schoolClass->name,
                    'section_id'   => $section->id,
                    'section_name' => $section->name,
                    'subject_id'   => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'teacher_id'   => $existing?->teacher_id,
                    'teacher_name' => $existing?->teacher?->full_name,
                ];
            }
        }

        return view('school-admin.subject-allocations.index', compact('rows', 'teachers'));
    }

    public function create()
    {
    
        $subjects = Subject::with('schoolClass.sections')->orderBy('subject_name')->get();
        $teachers = Teacher::where('is_active', true)->orderBy('first_name')->get();

        return view('school-admin.subject-allocations.create', compact('subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        TeacherSubjectAllocation::updateOrCreate(
            [
                'subject_id' => $validated['subject_id'],
                'section_id' => $validated['section_id'],
            ],
            [
                'teacher_id' => $validated['teacher_id'],
                'school_id'  => auth()->user()->school_id,
            ]
        );

        return redirect()
            ->route('school-admin.subject-allocations.index')
            ->with('success', 'Subject assigned successfully.');
    }
}