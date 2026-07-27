<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherAssignment;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResultController extends Controller
{
    /**
     * Teacher: Multiple students ko marks save/update
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json([
                'message' => 'Only teachers can enter marks.'
            ], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json([
                'message' => 'Teacher profile not found.'
            ], 404);
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'exam_name' => 'required|string|in:First Term,Second Term,Third Term,Weekly Test',
            'subject_id' => 'required|exists:subjects,id',
            'full_marks' => 'required|numeric|min:1',
            'records' => 'required|array|min:1',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.marks_obtained' => 'required|numeric|min:0',
            'records.*.remarks' => 'nullable|string|max:255',
        ]);

        $assignment = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'This class-section is not assigned to you.'
            ], 403);
        }

        // SECURITY: subject yehi class ko ho ki check
        $subjectBelongsToClass = Subject::where('id', $validated['subject_id'])
            ->where('class_id', $validated['class_id'])
            ->exists();

        if (!$subjectBelongsToClass) {
            return response()->json([
                'message' => 'This subject does not belong to the selected class.'
            ], 422);
        }

        $requestedIds = collect($validated['records'])
            ->pluck('student_id')
            ->unique();

        $validStudentIds = Student::where('school_id', $user->school_id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->whereIn('id', $requestedIds)
            ->pluck('id')
            ->toArray();

        $savedCount = 0;

        foreach ($validated['records'] as $record) {

            if (!in_array($record['student_id'], $validStudentIds)) {
                continue;
            }

            Result::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'exam_name' => $validated['exam_name'],
                    'subject_id' => $validated['subject_id'],
                ],
                [
                    'school_id' => $user->school_id,
                    'teacher_id' => $teacher->id,
                    'marks_obtained' => $record['marks_obtained'],
                    'full_marks' => $validated['full_marks'],
                    'remarks' => $record['remarks'] ?? null,
                ]
            );

            $savedCount++;
        }

        if ($savedCount === 0) {
            return response()->json([
                'message' => 'No valid students found to save marks for.'
            ], 422);
        }

        return response()->json([
            'message' => 'Marks saved successfully.',
            'saved' => $savedCount
        ]);
    }

    /**
     * Teacher: View Result by Exam (specific class-section)
     */
    public function viewByExam(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json([
                'message' => 'Only teachers can access this.'
            ], 403);
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'exam_name' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json([
                'message' => 'Teacher profile not found.'
            ], 404);
        }

        $assignment = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'This class-section is not assigned to you.'
            ], 403);
        }

        $results = Result::where('exam_name', $validated['exam_name'])
            ->where('subject_id', $validated['subject_id'])
            ->whereHas('student', function ($query) use ($validated) {
                $query->where('class_id', $validated['class_id'])
                      ->where('section_id', $validated['section_id']);
            })
            ->with('student:id,first_name,middle_name,last_name,roll_number')
            ->get();

        return response()->json(
            $results->map(function ($result) {
                return [
                    'id' => $result->id,
                    'student_id' => $result->student_id,
                    'student_name' => $result->student?->full_name,
                    'roll_number' => $result->student?->roll_number,
                    'marks_obtained' => $result->marks_obtained,
                    'full_marks' => $result->full_marks,
                    'remarks' => $result->remarks,
                ];
            })
        );
    }

    /**
     * Student: My Results
     * Terminal exam (first/second/final term): class ka SABAI subject dekhaune (marks bhare/nabhare pani)
     * Weekly test: jati result entry cha tyati matra dekhaune
     */
    public function myResults(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'message' => 'Only students can access this.'
            ], 403);
        }

        $student = Student::with('schoolClass:id,name')->where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student profile not found.'
            ], 404);
        }

        // Student ko class ka sabai subjects (terminal exam ko lagi complete list)
        $allSubjects = Subject::where('class_id', $student->class_id)
            ->orderBy('subject_name')
            ->get(['id', 'subject_name']);

        $results = Result::where('student_id', $student->id)
            ->get(['exam_name', 'subject_id', 'marks_obtained', 'full_marks', 'remarks']);

        // Terminal exam: sabai subject dekhaune, marks nabhaye "not entered"
        $buildTermResult = function ($examName) use ($allSubjects, $results) {
            $examResults = $results->where('exam_name', $examName)->keyBy('subject_id');

            return $allSubjects->map(function ($subject) use ($examResults) {
                $result = $examResults->get($subject->id);

                return [
                    'subject' => $subject->subject_name,
                    'marks_obtained' => $result->marks_obtained ?? null,
                    'full_marks' => $result->full_marks ?? null,
                    'remarks' => $result->remarks ?? null,
                    'status' => $result ? 'entered' : 'not entered',
                ];
            })->values();
        };

        // Weekly test: jati marks bhariyeko cha tyati matra
        $weeklyTest = $results->where('exam_name', 'Weekly Test')
            ->map(function ($result) use ($allSubjects) {
                $subject = $allSubjects->firstWhere('id', $result->subject_id);
                return [
                    'subject' => $subject->subject_name ?? null,
                    'marks_obtained' => $result->marks_obtained,
                    'full_marks' => $result->full_marks,
                    'remarks' => $result->remarks,
                ];
            })->values();

        return response()->json([
            'class_id' => $student->class_id,
            'class_name' => $student->schoolClass?->name,
            'terminal_examination' => [
                'first_term' => $buildTermResult('First Term'),
                'second_term' => $buildTermResult('Second Term'),
                'final_term' => $buildTermResult('Third Term'),
            ],
            'weekly_test' => $weeklyTest,
        ]);
    }
}