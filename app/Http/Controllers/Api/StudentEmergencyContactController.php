<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherAssignment;
use App\Models\EmergencyContact;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentEmergencyContactController extends Controller
{
    /**
     * Combined emergency-contact list for the logged-in student:
     *   1. Dynamic: the student's own Class Teacher (from ClassTeacherAssignment,
     *      resolved via the student's class_id + section_id).
     *   2. Static: Principal / Accounts / Receptionist etc. from the
     *      EmergencyContact table (school-wide, admin-managed).
     */
    public function index(Request $request): JsonResponse
    {
        $student = Student::where('user_id', $request->user()->id)->first();

        if (!$student) {
            return response()->json([
                'message' => 'No student profile linked to this account.',
            ], 404);
        }

        $contacts = collect();

        // 1. Class Teacher (dynamic, based on student's class/section)
        $assignment = ClassTeacherAssignment::with('teacher')
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->first();

        if ($assignment && $assignment->teacher) {
            $contacts->push([
                'label' => 'Class Teacher',
                'name'  => $assignment->teacher->full_name,
                'phone' => $assignment->teacher->phone,
            ]);
        }

        // 2. Static school-wide contacts (Principal, Accounts, Receptionist, ...)
        $staticContacts = EmergencyContact::orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (EmergencyContact $c) => [
                'label' => $c->designation,
                'name'  => $c->name,
                'phone' => $c->phone,
            ]);

        $contacts = $contacts->merge($staticContacts)->values();

        return response()->json([
            'contacts' => $contacts,
        ]);
    }
}