<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimetableController extends Controller
{
    /**
     * Teacher/Student: class ko timetable herne
     * Teacher le ?class=Grade5 pathaunu parcha, Student le aafai class automatic huncha
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'student') {
            $student = Student::with('schoolClass:id,name')->where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json(['message' => 'Student profile not found.'], 404);
            }

            $class = $student->schoolClass?->name;
        } else {
            $class = $request->query('class');
        }

        if (!$class) {
            return response()->json(['message' => 'Class not specified.'], 422);
        }

        $timetable = Timetable::where('class', $class)
            ->orderByRaw("FIELD(day, 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
            ->orderBy('period')
            ->get(['id', 'day', 'period', 'subject', 'start_time', 'end_time']);

        return response()->json([
            'class' => $class,
            'timetable' => $timetable,
        ]);
    }
}