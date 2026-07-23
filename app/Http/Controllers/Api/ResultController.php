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

        $assignment = ClassTeacherAssignment::where('teacher_id', $teacher->id)->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'You are not assigned as a class teacher.'
            ], 403);
        }

        $validated = $request->validate([
            'exam_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'full_marks' => 'required|numeric|min:1',
            'records' => 'required|array|min:1',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.marks_obtained' => 'required|numeric|min:0',
            'records.*.remarks' => 'nullable|string|max:255',
        ]);

        $requestedIds = collect($validated['records'])
            ->pluck('student_id')
            ->unique();

        $validStudentIds = Student::where('school_id', $user->school_id)
            ->where('class_id', $assignment->class_id)
            ->where('section_id', $assignment->section_id)
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
     * Teacher: View Result by Exam
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
            'exam_name' => 'required|string',
            'subject' => 'required|string',
        ]);

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json([
                'message' => 'Teacher profile not found.'
            ], 404);
        }

        $assignment = ClassTeacherAssignment::where('teacher_id', $teacher->id)->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'You are not assigned as a class teacher.'
            ], 403);
        }

        $results = Result::where('exam_name', $validated['exam_name'])
            ->where('subject', $validated['subject'])
            ->whereHas('student', function ($query) use ($assignment) {
                $query->where('class_id', $assignment->class_id)
                      ->where('section_id', $assignment->section_id);
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
            ->orderBy('exam_name')
            ->get([
                'exam_name',
                'subject',
                'marks_obtained',
                'full_marks',
                'remarks'
            ]);

        return response()->json($results);
    }
}