<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherAssignment;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeworkController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $homeworks = Homework::where('teacher_id', $teacher->id)
            ->with('schoolClass:id,name')
            ->orderBy('due_date', 'desc')
            ->get();

        $homeworks->each(function ($hw) {
            $hw->class_name = $hw->schoolClass?->name;
        });

        return response()->json($homeworks);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can assign homework.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $assigned = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->exists();

        if (!$assigned) {
            return response()->json(['message' => 'This class is not assigned to you.'], 403);
        }

        $homework = Homework::create([
            'school_id' => $user->school_id,
            'teacher_id' => $teacher->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'class_id' => $validated['class_id'],
            'subject' => $validated['subject'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        $homework->load('schoolClass:id,name');
        $homework->class_name = $homework->schoolClass?->name;

        return response()->json([
            'message' => 'Homework assigned successfully.',
            'homework' => $homework,
        ], 201);
    }

    
    public function destroy(Request $request, Homework $homework): JsonResponse
    {
        $user = $request->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher || $homework->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $homework->delete();

        return response()->json(['message' => 'Homework deleted.']);
    }

    
    public function myHomework(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::with('schoolClass:id,name')->where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $query = Homework::where('school_id', $user->school_id)
            ->where('class_id', $student->class_id)
            ->orderBy('due_date', 'desc');

       
        if ($request->filled('status')) {
            $status = $request->query('status');
            $query->whereHas('submissions', function ($q) use ($status, $student) {
                $q->where('student_id', $student->id)->where('status', $status);
            });
        }

        $homeworks = $query->get()->map(function ($hw) use ($student) {
            $submission = HomeworkSubmission::firstOrCreate(
                ['homework_id' => $hw->id, 'student_id' => $student->id],
                ['status' => 'pending']
            );

            return [
                'id' => $hw->id,
                'title' => $hw->title,
                'description' => $hw->description,
                'subject' => $hw->subject,
                'due_date' => $hw->due_date,
                'status' => $submission->status,
                'submitted_at' => $submission->submitted_at,
            ];
        });

        return response()->json([
            'class_id' => $student->class_id,
            'class_name' => $student->schoolClass?->name,
            'homeworks' => $homeworks,
        ]);
    }

    
    public function markComplete(Request $request, Homework $homework): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        if ($homework->school_id !== $user->school_id || $homework->class_id !== $student->class_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $submission = HomeworkSubmission::updateOrCreate(
            ['homework_id' => $homework->id, 'student_id' => $student->id],
            ['status' => 'completed', 'submitted_at' => now()]
        );

        return response()->json([
            'message' => 'Homework marked as completed.',
            'submission' => $submission,
        ]);
    }
}