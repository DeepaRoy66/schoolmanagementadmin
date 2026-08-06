<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassChangeController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();

        $students = collect();
        $selectedStudent = null;
        $isIndividual = $request->boolean('is_individual');

        if ($request->filled('class_from')) {
            $query = Student::with('section', 'academicYear')
                ->where('class_id', $request->class_from);

            if (!$isIndividual && $request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }

            if ($request->input('order_by') === 'roll_number') {
                $query->orderBy('roll_number');
            } else {
                $query->orderBy('first_name');
            }

            $students = $query->get();

            if ($isIndividual && $request->filled('student_id')) {
                $selectedStudent = $students->firstWhere('id', $request->student_id);
            }
        }

        return view('school-admin.class-change.index', compact(
            'classes', 'sections', 'academicYears', 'students', 'isIndividual', 'selectedStudent'
        ));
    }

    public function update(Request $request)
    {
        $isIndividual = $request->boolean('is_individual');

        if ($isIndividual) {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'class_id' => 'required|exists:classes,id',
                'academic_year_id' => 'nullable|exists:academic_years,id',
                'section_id' => 'nullable|exists:sections,id',
                'roll_number' => 'nullable|string',
                'status' => 'nullable|string',
            ]);

            $student = Student::find($validated['student_id']);
            $student->update([
                'class_id' => $validated['class_id'],
                'academic_year_id' => $validated['academic_year_id'] ?? $student->academic_year_id,
                'section_id' => $validated['section_id'] ?? $student->section_id,
                'roll_number' => $validated['roll_number'] ?? $student->roll_number,
                'status' => $validated['status'] ?? $student->status,
            ]);

            return redirect()
                ->route('school-admin.class-change.index')
                ->with('success', 'Student updated successfully.');
        }

        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'section_id' => 'nullable|array',
            'roll_number' => 'nullable|array',
            'status' => 'nullable|array',
        ]);

        $classTo = $request->input('class_to');
        $academicYearTo = $request->input('academic_year_to');

        foreach ($validated['student_ids'] as $studentId) {
            $student = Student::find($studentId);
            if (!$student) {
                continue;
            }

            $student->update([
                'class_id' => $classTo ?: $student->class_id,
                'academic_year_id' => $academicYearTo ?: $student->academic_year_id,
                'section_id' => $request->input("section_id.$studentId", $student->section_id),
                'roll_number' => $request->input("roll_number.$studentId", $student->roll_number),
                'status' => $request->input("status.$studentId", $student->status),
            ]);
        }

        return redirect()
            ->route('school-admin.class-change.index')
            ->with('success', 'Students updated successfully.');
    }
}