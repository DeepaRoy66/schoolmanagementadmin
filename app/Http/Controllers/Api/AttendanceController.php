<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    /**
     * Teacher: aafno school ko student list dine (attendance mark garna)
     */
    public function students(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $students = Student::where('school_id', $user->school_id)
            ->select('id', 'name', 'class', 'roll_number')
            ->orderBy('name')
            ->get();

        return response()->json($students);
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

        $validated = $request->validate([
            'date' => 'required|date',
            'records' => 'required|array|min:1',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:present,absent,leave',
            'records.*.remarks' => 'nullable|string|max:255',
        ]);

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        // SECURITY: aafno school ko student_id haru matra allowed list ma nikalne
        $requestedIds = collect($validated['records'])->pluck('student_id')->unique();

        $validStudentIds = Student::where('school_id', $user->school_id)
            ->whereIn('id', $requestedIds)
            ->pluck('id')
            ->toArray();

        $savedCount = 0;

        foreach ($validated['records'] as $record) {
            // SECURITY: yo student real ma logged-in teacher ko school ko ho ki check
            if (!in_array($record['student_id'], $validStudentIds)) {
                continue; // arko school ko student ho, silently skip
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
     * Teacher: euta specific din ko attendance herne (aafno school ko matra)
     */
    public function viewByDate(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        // SECURITY: school_id filter thapiyo - aghi yo thiyena (data leak thiyo)
        $attendance = Attendance::where('date', $validated['date'])
            ->where('school_id', $user->school_id)
            ->with('student:id,name,roll_number')
            ->get(['id', 'student_id', 'status', 'remarks']);

        return response()->json($attendance);
    }
}