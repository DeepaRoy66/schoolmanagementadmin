<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Homework;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherDashboardController extends Controller
{
    /**
     * Teacher Dashboard: welcome message, aaja ko attendance count, pending homework count
     */
    public function summary(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $today = now()->toDateString();

        $todayAttendanceCount = Attendance::where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->count();

        $pendingHomeworkCount = Homework::where('teacher_id', $teacher->id)
            ->where(function ($query) use ($today) {
                $query->whereNull('due_date')
                      ->orWhere('due_date', '>=', $today);
            })
            ->count();

        return response()->json([
            'welcome_message' => "Welcome, {$user->name}",
            'today_attendance_count' => $todayAttendanceCount,
            'pending_homework_count' => $pendingHomeworkCount,
        ]);
    }

    /**
     * Teacher: total classes count (class-teacher bhaeko class matra)
     */
    public function totalClasses(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $totalClasses = $teacher->isClassTeacher() ? 1 : 0;

        return response()->json([
            'total_classes' => $totalClasses,
            'class' => $teacher->class_teacher_of_class,
            'section' => $teacher->class_teacher_of_section,
        ]);
    }
}