<?php

namespace App\Http\Controllers;

use App\Models\ClassTeacherAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectAllocation;
use Illuminate\Http\Request;

class SubjectAllocationController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $selectedClassId = $request->integer('class_id') ?: null;
        $selectedSectionId = $request->integer('section_id') ?: null;

        $sections = collect();
        if ($selectedClassId) {
            $selectedClass = SchoolClass::find($selectedClassId);
            $sections = $selectedClass?->sections()->orderBy('name')->get() ?? collect();
        }

        $teachers = Teacher::where('is_active', true)->orderBy('first_name')->get();

        $classTeacher = null;
        $rows = [];

        if ($selectedClassId && $selectedSectionId) {
            $classTeacher = ClassTeacherAssignment::with('teacher')
                ->where('class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->first();

            $subjects = Subject::where('class_id', $selectedClassId)
                ->orderBy('subject_name')
                ->get();

            $allocations = TeacherSubjectAllocation::where('section_id', $selectedSectionId)
                ->whereIn('subject_id', $subjects->pluck('id'))
                ->get()
                ->keyBy('subject_id');

            foreach ($subjects as $subject) {
                $existing = $allocations->get($subject->id);

                $rows[] = [
                    'subject_id'    => $subject->id,
                    'subject_name'  => $subject->subject_name,
                    'subject_code'  => $subject->subject_code,
                    'teacher_id'    => $existing?->teacher_id,
                    'allocation_id' => $existing?->id,
                ];
            }
        }

        return view('school-admin.subject-allocations.index', [
            'classes'           => $classes,
            'sections'          => $sections,
            'teachers'          => $teachers,
            'rows'              => $rows,
            'classTeacher'      => $classTeacher,
            'selectedClassId'   => $selectedClassId,
            'selectedSectionId' => $selectedSectionId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id'   => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
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
            ->route('school-admin.subject-allocations.index', [
                'class_id'   => $validated['class_id'],
                'section_id' => $validated['section_id'],
            ])
            ->with('success', 'Teacher assigned successfully.');
    }

    public function destroy(Request $request, TeacherSubjectAllocation $allocation)
    {
        $classId = $request->query('class_id');
        $sectionId = $request->query('section_id');

        $allocation->delete();

        return redirect()
            ->route('school-admin.subject-allocations.index', [
                'class_id'   => $classId,
                'section_id' => $sectionId,
            ])
            ->with('success', 'Teacher unassigned successfully.');
    }
}