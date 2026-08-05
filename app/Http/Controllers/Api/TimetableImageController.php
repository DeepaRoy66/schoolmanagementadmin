<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TimetableImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimetableImageController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'student') {
            $student = Student::with(['schoolClass:id,name', 'section:id,name'])->where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json(['message' => 'Student profile not found.'], 404);
            }

            $classId = $student->class_id;
            $sectionId = $student->section_id;
        } else {
            $classId = $request->query('class_id');
            $sectionId = $request->query('section_id');
        }

        if (!$classId || !$sectionId) {
            return response()->json(['message' => 'Class or section not specified.'], 422);
        }

        $latest = TimetableImage::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->latest()
            ->first();

        if (!$latest) {
            return response()->json(['message' => 'No timetable uploaded yet for this class.'], 404);
        }

        return response()->json([
            'image_url' => asset('storage/' . $latest->file_path),
            'uploaded_at' => $latest->created_at,
        ]);
    }
}