<?php

namespace App\Http\Controllers\Api;
use App\Models\Teacher;
use App\Models\ClassTeacherAssignment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login - email/password linchha, token dincha
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // License check - school_admin/teacher/student ko lagi
        if ($user->role !== 'super_admin') {
            $school = $user->school;

            if (!$school) {
                return response()->json([
                    'message' => 'Your account is not linked to any school.',
                ], 403);
            }

            $isExpired = $school->license_status === 'expired'
                || ($school->license_expiry && \Carbon\Carbon::parse($school->license_expiry)->isPast());

            if ($isExpired) {
                return response()->json([
                    'message' => 'Your school\'s license has expired. Please contact the administrator.',
                ], 403);
            }
        }

        // Naya token banaune
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'school_id' => $user->school_id,
                'school_name' => $user->school->name ?? null,
            ],
        ]);
    }

    /**
     * Login gareko user ko info dine
     */

public function me(Request $request): JsonResponse
{
    $user = $request->user();

    $response = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'school_id' => $user->school_id,
        'school_name' => $user->school->name ?? null,
    ];

    // Teacher ko lagi assigned classes pathaune
    if ($user->role === 'teacher') {

        $teacher = Teacher::where('user_id', $user->id)->first();

        if ($teacher) {

            $response['assigned_classes'] = ClassTeacherAssignment::with('schoolClass:id,name')
                ->where('teacher_id', $teacher->id)
                ->get()
                ->unique('class_id')
                ->map(function ($item) {
                    return [
                        'class_id' => $item->class_id,
                        'class_name' => $item->schoolClass?->name,
                    ];
                })
                ->values();
        }
    }

    return response()->json($response);
}
    /**
     * Logout - current token delete garne
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}