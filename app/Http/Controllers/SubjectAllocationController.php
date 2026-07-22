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
        // Har subject afai class-specific cha, so subject.schoolClass bata class naam aaucha
        // Har subject ko class ma jati section cha (class_section pivot bata), tini sabai rows banaune
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
        // create.blade.php needs: $subjects (with schoolClass + schoolClass.sections
        // for the JS subject->section mapping) and $teachers for the dropdown.
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

        // create.blade.php is a normal form submission (uses $errors / old() and
        // expects a redirect back to the index), not an AJAX call, so this now
        // redirects with a flash message instead of returning JSON.
        return redirect()
            ->route('school-admin.subject-allocations.index')
            ->with('success', 'Subject assigned successfully.');
    }
}