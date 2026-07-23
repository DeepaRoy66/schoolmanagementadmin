<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ClassTeacherAssignment;

class AttendanceController extends Controller
{
    /**
     * Teacher: aafno assigned classes ra sections ko list dine
     */
    public function assignedClasses(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $assignments = ClassTeacherAssignment::with(['schoolClass:id,name', 'section:id,name'])
            ->where('teacher_id', $teacher->id)
            ->get();

        return response()->json(
            $assignments->map(function ($assignment) {
                return [
                    'class_id' => $assignment->class_id,
                    'class_name' => $assignment->schoolClass?->name,
                    'section_id' => $assignment->section_id,
                    'section_name' => $assignment->section?->name,
                ];
            })
        );
    }

    /**
     * Teacher: given class-section ko student list dine (attendance mark garna)
     */
    public function students(Request $request): JsonResponse
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
                    'id' => $student->id,
                    'name' => $student->full_name,
                    'roll_number' => $student->roll_number,
                    'class' => $student->schoolClass?->name,
                    'section' => $student->section?->name,
                ];
            })
        );
    }

    /**
     * Teacher: attendance mark garne (euta din, multiple students)
     */
    public function markAttendance(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can mark attendance.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'records' => 'required|array|min:1',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:present,absent,leave',
            'records.*.remarks' => 'nullable|string|max:255',
        ]);

        // SECURITY: aafno class-section ko student_id haru matra allowed list ma nikalne
        $requestedIds = collect($validated['records'])->pluck('student_id')->unique();

        $assignment = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'No class assigned to this teacher.'
            ], 403);
        }

        $validStudentIds = Student::where('school_id', $user->school_id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->whereIn('id', $requestedIds)
            ->pluck('id')
            ->toArray();

        $savedCount = 0;

        foreach ($validated['records'] as $record) {
            // SECURITY: yo student real ma logged-in teacher ko class-section ko ho ki check
            if (!in_array($record['student_id'], $validStudentIds)) {
                continue; // arko class/section ko student ho, silently skip
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'date' => $validated['date'],
                ],
                [
                    'school_id' => $user->school_id,
                    'teacher_id' => $teacher->id,
                    'status' => $record['status'],
                    'remarks' => $record['remarks'] ?? null,
                ]
            );

            $savedCount++;
        }

        if ($savedCount === 0) {
            return response()->json(['message' => 'No valid students found to mark attendance for.'], 422);
        }

        return response()->json([
            'message' => 'Attendance saved successfully.',
            'saved' => $savedCount,
        ]);
    }

    /**
     * Student: aafno attendance history herne
     */
    public function myAttendance(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $attendance = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get(['date', 'status', 'remarks']);

        return response()->json($attendance);
    }

    /**
     * NAYA: Student attendance summary (total/present/absent/leave)
     */
    public function myAttendanceSummary(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $counts = Attendance::where('student_id', $student->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            'total_days' => $counts->sum(),
            'present' => $counts->get('present', 0),
            'absent' => $counts->get('absent', 0),
            'leave' => $counts->get('leave', 0),
        ]);
    }

    /**
     * Teacher: euta specific din ko attendance herne (aafno assigned class-section ko matra)
     */
    public function viewByDate(Request $request): JsonResponse
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
            'date' => 'required|date',
        ]);

        // SECURITY: yo class-section teacher lai assign bhako ho ki check
        $assigned = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->exists();

        if (!$assigned) {
            return response()->json(['message' => 'This class is not assigned to you.'], 403);
        }

        $attendance = Attendance::where('date', $validated['date'])
            ->where('school_id', $user->school_id)
            ->whereHas('student', function ($query) use ($validated) {
                $query->where('class_id', $validated['class_id'])
                    ->where('section_id', $validated['section_id']);
            })
            ->with('student:id,first_name,middle_name,last_name,roll_number')
            ->get(['id', 'student_id', 'status', 'remarks']);

        return response()->json(
            $attendance->map(function ($record) {
                return [
                    'id' => $record->id,
                    'student_id' => $record->student_id,
                    'student_name' => $record->student?->full_name,
                    'roll_number' => $record->student?->roll_number,
                    'status' => $record->status,
                    'remarks' => $record->remarks,
                ];
            })
        );
    }

    public function sections(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json([
                'message' => 'Only teachers can access this.'
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
        ]);

        $sections = ClassTeacherAssignment::with('section:id,name')
            ->where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->get()
            ->map(function ($item) {
                return [
                    'section_id' => $item->section_id,
                    'section_name' => $item->section?->name,
                ];
            });

        return response()->json($sections);
    }
}