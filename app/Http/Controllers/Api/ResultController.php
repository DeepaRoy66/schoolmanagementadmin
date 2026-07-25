<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherAssignment;
use App\Models\Result;
use App\Models\Student;
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
            'subject' => 'required|string|max:255',
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
                    'subject' => $validated['subject'],
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
            'subject' => 'required|string',
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
            ->where('subject', $validated['subject'])
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
     * Student: My Results (grouped: terminal_examination -> first/second/third_term, + weekly_test)
     */
    public function myResults(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'message' => 'Only students can access this.'
            ], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student profile not found.'
            ], 404);
        }

        $results = Result::where('student_id', $student->id)
            ->get(['exam_name', 'subject', 'marks_obtained', 'full_marks', 'remarks']);

        $formatSubject = function ($result) {
            return [
                'subject' => $result->subject,
                'marks_obtained' => $result->marks_obtained,
                'full_marks' => $result->full_marks,
                'remarks' => $result->remarks,
            ];
        };

        $firstTerm = $results->where('exam_name', 'First Term')->map($formatSubject)->values();
        $secondTerm = $results->where('exam_name', 'Second Term')->map($formatSubject)->values();
        $thirdTerm = $results->where('exam_name', 'Third Term')->map($formatSubject)->values();
        $weeklyTest = $results->where('exam_name', 'Weekly Test')->map($formatSubject)->values();

        return response()->json([
            'terminal_examination' => [
                'first_term' => $firstTerm,
                'second_term' => $secondTerm,
                'third_term' => $thirdTerm,
            ],
            'weekly_test' => $weeklyTest,
        ]);
    }
}