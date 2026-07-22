<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::with(['schoolClass', 'section'])->latest()->paginate(10);

        return view('school-admin.students.index', compact('students'));
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        return view('school-admin.students.create', compact('classes', 'sections'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'required|string|email|max:255|unique:students,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,dropped_out',
            'password' => 'required|string|min:8',
        ]);

        $schoolId = auth()->user()->school_id;
        $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);

        $user = User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'school_id' => $schoolId,
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'school_id' => $schoolId,
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'dob' => $validated['dob'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'student_uid' => Student::generateStudentUid(),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'roll_number' => $validated['roll_number'] ?? null,
            'status' => $validated['status'],
            'is_active' => $validated['status'] === 'active',
        ]);

        return redirect()->route('school-admin.students.index')
            ->with('status', 'Student successfully added. They can now log in with their email and password.');
    }

    public function show(Student $student): View
    {
        return view('school-admin.students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        return view('school-admin.students.edit', compact('student', 'classes', 'sections'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,dropped_out',
        ]);

        $validated['is_active'] = $validated['status'] === 'active';

        $student->update($validated);

        if ($student->user) {
            $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);
            $student->user->update([
                'name' => $fullName,
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('school-admin.students.index')
            ->with('status', 'Student successfully updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        return redirect()->route('school-admin.students.index')
            ->with('status', 'Student deleted.');
    }
}