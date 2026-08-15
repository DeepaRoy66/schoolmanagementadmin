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


        $user->update(['last_login_at' => now()]);

        
        $token = $user->createToken('mobile-app')->plainTextToken;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'must_change_password' => (bool) $user->must_change_password,
            'photo' => null,
            'school_id' => $user->school_id,
            'school_name' => $user->school->name ?? null,
        ];

        
        if ($user->role === 'teacher') {
            $teacher = Teacher::where('user_id', $user->id)->first();

            $userData['photo'] = $teacher && $teacher->photo ? asset('storage/' . $teacher->photo) : null;

            $classTeacherAssignment = $teacher
                ? ClassTeacherAssignment::with(['schoolClass:id,name', 'section:id,name'])
                    ->where('teacher_id', $teacher->id)
                    ->first()
                : null;

            $userData['is_class_teacher'] = $classTeacherAssignment !== null;

            $userData['class_teacher_of'] = $classTeacherAssignment ? [
                'class_id' => $classTeacherAssignment->class_id,
                'class_name' => $classTeacherAssignment->schoolClass?->name,
                'section_id' => $classTeacherAssignment->section_id,
                'section_name' => $classTeacherAssignment->section?->name,
            ] : null;
        }

        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();

            $userData['photo'] = $student && $student->photo ? asset('storage/' . $student->photo) : null;
        }

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $userData,
        ]);
    }

    
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'must_change_password' => (bool) $user->must_change_password,
            'photo' => null,
            'school_id' => $user->school_id,
            'school_name' => $user->school->name ?? null,
        ];

        if ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {

                $response['photo'] = $teacher->photo ? asset('storage/' . $teacher->photo) : null;

                // Subjects this teacher is allocated to, per (class_id, section_id).
                $subjectAllocations = TeacherSubjectAllocation::with([
                        'subject:id,class_id,subject_name,subject_code',
                        'section:id,name',
                    ])
                    ->where('teacher_id', $teacher->id)
                    ->get()
                    ->filter(fn($alloc) => $alloc->subject !== null && $alloc->section !== null);

                // Class-teacher assignments for this teacher.
                $classTeacherAssignments = ClassTeacherAssignment::with(['schoolClass:id,name', 'section:id,name'])
                    ->where('teacher_id', $teacher->id)
                    ->get();

                // Quick lookup: "class_id-section_id" => true, for class-teacher sections.
                $classTeacherKeys = $classTeacherAssignments->mapWithKeys(
                    fn($a) => ["{$a->class_id}-{$a->section_id}" => true]
                );

                // Group subject allocations by class_id + section_id (a teacher can teach
                // multiple subjects in the same section, so group before building the list).
                $bySection = $subjectAllocations->groupBy(
                    fn($alloc) => "{$alloc->subject->class_id}-{$alloc->section_id}"
                );

                $assignedClasses = collect();

                foreach ($bySection as $key => $allocations) {
                    [$classId, $sectionId] = explode('-', $key);
                    $first = $allocations->first();

                    $assignedClasses->push([
                        'class_id' => (int) $classId,
                        'class_name' => $first->subject->schoolClass?->name,
                        'section_id' => (int) $sectionId,
                        'section_name' => $first->section->name,
                        'is_class_teacher' => $classTeacherKeys->has($key),
                        'subjects' => $allocations
                            ->map(fn($alloc) => [
                                'subject_id' => $alloc->subject->id,
                                'subject_name' => $alloc->subject->subject_name,
                                'subject_code' => $alloc->subject->subject_code,
                            ])
                            ->unique('subject_id')
                            ->values(),
                    ]);
                }

                // Add class-teacher assignments that have no subject allocation at all
                // in that section (e.g. class teacher who doesn't teach a subject there).
                foreach ($classTeacherAssignments as $cta) {
                    $key = "{$cta->class_id}-{$cta->section_id}";

                    if (!$bySection->has($key)) {
                        $assignedClasses->push([
                            'class_id' => $cta->class_id,
                            'class_name' => $cta->schoolClass?->name,
                            'section_id' => $cta->section_id,
                            'section_name' => $cta->section?->name,
                            'is_class_teacher' => true,
                            'subjects' => [],
                        ]);
                    }
                }

                $response['assigned_classes'] = $assignedClasses->values();
                $response['is_class_teacher'] = $classTeacherAssignments->isNotEmpty();

                // Direct "which class/section" info, so the app doesn't have to
                // loop through assigned_classes to find the is_class_teacher: true entry.
                $firstAssignment = $classTeacherAssignments->first();

                $response['class_teacher_of'] = $firstAssignment ? [
                    'class_id' => $firstAssignment->class_id,
                    'class_name' => $firstAssignment->schoolClass?->name,
                    'section_id' => $firstAssignment->section_id,
                    'section_name' => $firstAssignment->section?->name,
                ] : null;
            } else {
                $response['assigned_classes'] = [];
                $response['is_class_teacher'] = false;
                $response['class_teacher_of'] = null;
            }
        }

    
        if ($user->role === 'student') {

            $student = Student::with(['schoolClass:id,name', 'section:id,name'])
                ->where('user_id', $user->id)
                ->first();

            if ($student) {
                $response['photo'] = $student->photo ? asset('storage/' . $student->photo) : null;
                $response['class_id'] = $student->class_id;
                $response['class_name'] = $student->schoolClass?->name;
                $response['section_id'] = $student->section_id;
                $response['section_name'] = $student->section?->name;
                $response['roll_number'] = $student->roll_number;
            }
        }

        return response()->json($response);
    }

    
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
        $user->must_change_password = false;
        $user->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }

    
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}