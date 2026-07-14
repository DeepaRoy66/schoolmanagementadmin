<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeworkController extends Controller
{
    /**
     * Teacher: aafule assign gareko sabai homework list garne
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        $homeworks = Homework::where('teacher_id', $teacher->id)
            ->orderBy('due_date', 'desc')
            ->get();

        return response()->json($homeworks);
    }

    /**
     * Teacher: naya homework assign garne
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can assign homework.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $homework = Homework::create([
            'school_id' => $user->school_id,
            'teacher_id' => $teacher->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'class' => $validated['class'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        return response()->json([
            'message' => 'Homework assigned successfully.',
            'homework' => $homework,
        ], 201);
    }

    /**
     * Teacher: euta homework delete garne
     */
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

    /**
     * Student: aafno class ko homework list herne
     */
    public function myHomework(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $homeworks = Homework::where('school_id', $user->school_id)
            ->where('class', $student->class)
            ->orderBy('due_date', 'desc')
            ->get(['id', 'title', 'description', 'subject', 'due_date']);

        return response()->json($homeworks);
    }
}