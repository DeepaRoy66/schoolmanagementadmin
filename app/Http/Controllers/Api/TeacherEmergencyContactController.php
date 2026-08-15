<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherAssignment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherEmergencyContactController extends Controller
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

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        $assigned = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->exists();

        if (!$assigned) {
            return response()->json(['message' => 'This class is not assigned to you.'], 403);
        }

        $students = Student::with(['schoolClass:id,name', 'section:id,name'])
            ->where('school_id', $user->school_id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->orderBy('first_name')
            ->get();

        return response()->json(
            $students->map(function ($student) {
                return [
                    'student_id' => $student->id,
                    'name' => $student->full_name,
                    'roll_number' => $student->roll_number,
                    'class_id' => $student->class_id,
                    'class_name' => $student->schoolClass?->name,
                    'section_id' => $student->section_id,
                    'section_name' => $student->section?->name,
                    'own_phone' => $student->phone,
                    'mother_name' => $student->mother_name,
                    'mother_phone' => $student->mother_phone,
                    'father_name' => $student->father_name,
                    'father_phone' => $student->father_phone,
                    'local_guardian_name' => $student->local_guardian_name,
                    'local_guardian_phone' => $student->local_guardian_phone,
                ];
        })
        );
    }
}