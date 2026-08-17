<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherSubjectAllocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ClassTeacherAssignment;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{

    public function assignedClasses(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $assignments = ClassTeacherAssignment::with(['schoolClass:id,name', 'section:id,name'])
            ->where('teacher_id', $teacher->id)
            ->get();

        return response()->json(
            $assignments->map(function ($assignment) {
                return [
                    'class_id' => $assignment->class_id,
                    'class_name' => $assignment->schoolClass?->name,
                    'section_id' => $assignment->section_id,
                    'section_name' => $assignment->section?->name,
                ];
            })
        );
    }

    /**
     * Full student details for the class/section this teacher is the
     * class teacher of (photo, contact, parent/guardian, emergency
     * contacts, academic info — everything from the student profile).
     */
    public function students(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        $assigned = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->exists();

        if (!$assigned) {
            return response()->json(['message' => 'This class is not assigned to you.'], 403);
        }

        $students = Student::with(['schoolClass:id,name', 'section:id,name'])
            ->where('school_id', $user->school_id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->orderBy('first_name')
            ->get();

        return response()->json(
            $students->map(function ($student) {
                return [
                    // Identity
                    'student_id' => $student->id,
                    'user_id' => $student->user_id,
                    'student_uid' => $student->student_uid,
                    'photo_url' => $student->photo ? asset('storage/' . $student->photo) : null,

                    // Personal information
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'last_name' => $student->last_name,
                    'name' => $student->full_name,
                    'dob' => $student->dob?->format('Y-m-d'),
                    'gender' => $student->gender,

                    // Contact details
                    'phone' => $student->phone,
                    'email' => $student->email,
                    'address' => $student->address,

                    // Parent / guardian
                    'parent_name' => $student->parent_name,
                    'parent_phone' => $student->parent_phone,
                    'telephone_no' => $student->telephone_no,

                    // Emergency contacts
                    'mother_name' => $student->mother_name,
                    'mother_phone' => $student->mother_phone,
                    'father_name' => $student->father_name,
                    'father_phone' => $student->father_phone,
                    'local_guardian_name' => $student->local_guardian_name,
                    'local_guardian_phone' => $student->local_guardian_phone,
                    'emergency_contact_name' => $student->emergency_contact_name,
                    'emergency_contact_relation' => $student->emergency_contact_relation,
                    'emergency_contact_phone' => $student->emergency_contact_phone,

                    // Academic details
                    'class_id' => $student->class_id,
                    'class_name' => $student->schoolClass?->name,
                    'section_id' => $student->section_id,
                    'section_name' => $student->section?->name,
                    'roll_number' => $student->roll_number,
                    'status' => $student->status,
                ];
            })
        );
    }


    public function myTeachers(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }


        $subjectTeachers = TeacherSubjectAllocation::with('teacher:id,user_id,first_name,middle_name,last_name', 'subject:id,subject_name')
            ->where('section_id', $student->section_id)
            ->get()
            ->filter(fn($alloc) => $alloc->teacher !== null)
            ->map(function ($alloc) {
                return [
                    'teacher_id' => $alloc->teacher->id,
                    'user_id' => $alloc->teacher->user_id,
                    'name' => $alloc->teacher->full_name,
                    'subject' => $alloc->subject?->subject_name,
                    'role' => 'subject_teacher',
                ];
            });


        $classTeacherAssignment = ClassTeacherAssignment::with('teacher:id,user_id,first_name,middle_name,last_name')
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->first();

        $classTeacher = collect();
        if ($classTeacherAssignment && $classTeacherAssignment->teacher) {
            $classTeacher = collect([[
                'teacher_id' => $classTeacherAssignment->teacher->id,
                'user_id' => $classTeacherAssignment->teacher->user_id,
                'name' => $classTeacherAssignment->teacher->full_name,
                'subject' => null,
                'role' => 'class_teacher',
            ]]);
        }


        $allTeachers = $subjectTeachers->concat($classTeacher)
            ->unique('teacher_id')
            ->values();

        return response()->json($allTeachers);
    }

    public function markAttendance(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can mark attendance.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'records' => 'required|array|min:1',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:present,absent,leave',
            'records.*.remarks' => 'nullable|string|max:255',
        ]);

        $requestedIds = collect($validated['records'])->pluck('student_id')->unique();

        $assignment = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'No class assigned to this teacher.'
            ], 403);
        }

        $validStudentIds = Student::where('school_id', $user->school_id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->whereIn('id', $requestedIds)
            ->pluck('id')
            ->toArray();

        $savedCount = 0;
        $savedRecords = [];

        foreach ($validated['records'] as $record) {
            if (!in_array($record['student_id'], $validStudentIds)) {
                continue;
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

            $savedRecords[] = $record;
            $savedCount++;
        }

        if ($savedCount === 0) {
            return response()->json(['message' => 'No valid students found to mark attendance for.'], 422);
        }

        $this->sendAttendancePushNotifications($savedRecords, $validated['date']);

        return response()->json([
            'message' => 'Attendance saved successfully.',
            'saved' => $savedCount,
        ]);
    }

    /**
     * Attendance mark vaisakepachi, harek student lai (jasko onesignal_player_id
     * cha) status anusar push notification pathaune. Status anusar group garera
     * (present/absent/leave) euta-euta OneSignal call ma batch garincha.
     */
    private function sendAttendancePushNotifications(array $records, string $date): void
    {
        $appId = config('services.onesignal.app_id');
        $restApiKey = config('services.onesignal.rest_api_key');

        if (empty($appId) || empty($restApiKey)) {
            return;
        }

        $studentIds = collect($records)->pluck('student_id')->unique();

        $students = Student::with('user:id,onesignal_player_id')
            ->whereIn('id', $studentIds)
            ->get(['id', 'user_id'])
            ->keyBy('id');

        $byStatus = collect($records)->groupBy('status');

        $statusLabels = [
            'present' => 'Present',
            'absent' => 'Absent',
            'leave' => 'On Leave',
        ];

        foreach ($byStatus as $status => $group) {
            $playerIds = $group
                ->map(fn($record) => $students->get($record['student_id'])?->user?->onesignal_player_id)
                ->filter()
                ->values()
                ->toArray();

            if (empty($playerIds)) {
                continue;
            }

            $label = $statusLabels[$status] ?? ucfirst($status);

            Http::withHeaders([
                'Authorization' => 'Basic ' . $restApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id' => $appId,
                'include_player_ids' => $playerIds,
                'headings' => ['en' => 'Attendance Update'],
                'contents' => ['en' => "You were marked {$label} on {$date}."],
                'data' => ['type' => 'attendance', 'date' => $date, 'status' => $status],
            ]);
        }
    }


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


    public function myAttendanceSummary(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can access this.'], 403);
        }

        $student = Student::with('schoolClass:id,name')->where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $counts = Attendance::where('student_id', $student->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $records = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get(['date', 'status', 'remarks']);

        return response()->json([
            'class_id' => $student->class_id,
            'class_name' => $student->schoolClass?->name,
            'total_days' => $counts->sum(),
            'present' => $counts->get('present', 0),
            'absent' => $counts->get('absent', 0),
            'leave' => $counts->get('leave', 0),
            'records' => $records,
        ]);
    }


    public function viewByDate(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json(['message' => 'Only teachers can access this.'], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found.'], 404);
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
        ]);

        $assigned = ClassTeacherAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->exists();

        if (!$assigned) {
            return response()->json(['message' => 'This class is not assigned to you.'], 403);
        }

        $attendance = Attendance::where('date', $validated['date'])
            ->where('school_id', $user->school_id)
            ->whereHas('student', function ($query) use ($validated) {
                $query->where('class_id', $validated['class_id'])
                    ->where('section_id', $validated['section_id']);
            })
            ->with('student:id,first_name,middle_name,last_name,roll_number')
            ->get(['id', 'student_id', 'status', 'remarks']);

        return response()->json(
            $attendance->map(function ($record) {
                return [
                    'id' => $record->id,
                    'student_id' => $record->student_id,
                    'student_name' => $record->student?->full_name,
                    'roll_number' => $record->student?->roll_number,
                    'status' => $record->status,
                    'remarks' => $record->remarks,
                ];
            })
        );
    }

    public function sections(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teacher') {
            return response()->json([
                'message' => 'Only teachers can access this.'
            ], 403);
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return response()->json([
                'message' => 'Teacher profile not found.'
            ], 404);
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $sections = ClassTeacherAssignment::with('section:id,name')
            ->where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->get()
            ->map(function ($item) {
                return [
                    'section_id' => $item->section_id,
                    'section_name' => $item->section?->name,
                ];
            });

        return response()->json($sections);
    }
}