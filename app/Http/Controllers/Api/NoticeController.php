<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NoticeController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Notice::where('school_id', $user->school_id)
            ->with(['targets.schoolClass:id,name', 'targets.section:id,name'])
            ->where(function ($q) use ($user) {
                $q->where('target_type', 'all');

                if ($user->role === 'teacher') {
                    $q->orWhere('target_type', 'teacher');
                }

                if ($user->role === 'student') {
                    $q->orWhere('target_type', 'student');

                    $student = $user->student;

                    if ($student) {
                        $q->orWhere(function ($q2) use ($student) {
                            $q2->where('target_type', 'class_specific')
                               ->whereHas('targets', function ($q3) use ($student) {
                                   $q3->where('class_id', $student->class_id)
                                      ->where(function ($q4) use ($student) {
                                          $q4->whereNull('section_id')
                                             ->orWhere('section_id', $student->section_id);
                                      });
                               });
                        });
                    }
                }
            });

        $notices = $query->latest()
            ->paginate(15)
            ->through(function ($notice) {
                return [
                    'id' => $notice->id,
                    'title' => $notice->title,
                    'message' => $notice->message,
                    'target_type' => $notice->target_type,
                    'created_at' => $notice->created_at,
                ];
            });

        return response()->json($notices);
    }
}