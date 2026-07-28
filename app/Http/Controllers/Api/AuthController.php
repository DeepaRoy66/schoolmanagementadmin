<?php

namespace App\Http\Controllers\Api;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassTeacherAssignment;
use App\Models\TeacherSubjectAllocation;

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

        // Last login timestamp update garne
        $user->update(['last_login_at' => now()]);

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
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
            'school_id' => $user->school_id,
            'school_name' => $user->school->name ?? null,
        ];

        // Teacher ko lagi assigned classes + sections + subjects pathaune
        if ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {

                $subjectAllocations = TeacherSubjectAllocation::with('subject:id,class_id,subject_name,subject_code')
                    ->where('teacher_id', $teacher->id)
                    ->get();

                $subjectsByClass = $subjectAllocations
                    ->filter(fn($alloc) => $alloc->subject !== null)
                    ->groupBy(fn($alloc) => $alloc->subject->class_id)
                    ->map(function ($allocations) {
                        return $allocations->map(function ($alloc) {
                            return [
                                'subject_id' => $alloc->subject->id,
                                'subject_name' => $alloc->subject->subject_name,
                                'subject_code' => $alloc->subject->subject_code,
                            ];
                        })->unique('subject_id')->values();
                    });

                $response['assigned_classes'] = ClassTeacherAssignment::with(['schoolClass:id,name', 'section:id,name'])
                    ->where('teacher_id', $teacher->id)
                    ->get()
                    ->map(function ($item) use ($subjectsByClass) {
                        return [
                            'class_id' => $item->class_id,
                            'class_name' => $item->schoolClass?->name,
                            'section_id' => $item->section_id,
                            'section_name' => $item->section?->name,
                            'subjects' => $subjectsByClass->get($item->class_id, collect())->values(),
                        ];
                    })
                    ->values();
            }
        }

        // Student ko lagi aafno class/section pathaune
        if ($user->role === 'student') {

            $student = Student::with(['schoolClass:id,name', 'section:id,name'])
                ->where('user_id', $user->id)
                ->first();

            if ($student) {
                $response['class_id'] = $student->class_id;
                $response['class_name'] = $student->schoolClass?->name;
                $response['section_id'] = $student->section_id;
                $response['section_name'] = $student->section?->name;
                $response['roll_number'] = $student->roll_number;
            }
        }

        return response()->json($response);
    }

    /**
 * Password change garne (login vaisakepachi, App bhitra bata)
 */
public function changePassword(Request $request): JsonResponse
{
    $validated = $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = $request->user();

    if (!Hash::check($validated['current_password'], $user->password)) {
        return response()->json([
            'message' => 'Current password is incorrect.',
            'errors' => ['current_password' => ['Current password is incorrect.']],
        ], 422);
    }

    $user->password = Hash::make($validated['new_password']);
    $user->save();

    return response()->json(['message' => 'Password changed successfully.']);
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