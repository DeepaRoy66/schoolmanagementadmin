<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::latest()->paginate(10);

        return view('school-admin.students.index', compact('students'));
    }

    public function create(): View
    {
        return view('school-admin.students.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'class' => 'nullable|string|max:255',
            'roll_number' => 'nullable|string|max:50',
            'password' => 'required|string|min:8',
        ]);

        $schoolId = auth()->user()->school_id;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'school_id' => $schoolId,
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'school_id' => $schoolId,
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'class' => $validated['class'] ?? null,
            'roll_number' => $validated['roll_number'] ?? null,
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
        return view('school-admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'class' => 'nullable|string|max:255',
            'roll_number' => 'nullable|string|max:50',
        ]);

        $student->update($validated);

        if ($student->user) {
            $student->user->update([
                'name' => $validated['name'],
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