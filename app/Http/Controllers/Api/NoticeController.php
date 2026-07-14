<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NoticeController extends Controller
{
    /**
     * Teacher/Student: aafno school ko notices herne
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notices = Notice::where('school_id', $user->school_id)
            ->latest()
            ->get(['id', 'title', 'message', 'created_at']);

        return response()->json($notices);
    }
}