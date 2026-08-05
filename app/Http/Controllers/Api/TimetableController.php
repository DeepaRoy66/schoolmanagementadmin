<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimetableController extends Controller
{
    
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'student') {
            $student = Student::with(['schoolClass:id,name', 'section:id,name'])->where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json(['message' => 'Student profile not found.'], 404);
            }

            $classId = $student->class_id;
            $sectionId = $student->section_id;
            $className = $student->schoolClass?->name;
            $sectionName = $student->section?->name;
        } else {
            $classId = $request->query('class_id');
            $sectionId = $request->query('section_id');
            $className = null;
            $sectionName = null;
        }

        if (!$classId || !$sectionId) {
            return response()->json(['message' => 'Class or section not specified.'], 422);
        }

        
        $day = $request->query('day') ?: now()->format('l'); 

        $timetable = Timetable::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('day', $day)
            ->orderBy('period')
            ->get(['id', 'day', 'period', 'subject', 'start_time', 'end_time']);

        return response()->json([
            'class' => $className,
            'section' => $sectionName,
            'day' => $day,
            'timetable' => $timetable,
        ]);
    }
}