<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::latest()->paginate(10);

        return view('school-admin.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('school-admin.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        $schoolId = auth()->user()->school_id;

        // Pahila login account (User) banaune
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'school_id' => $schoolId,
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        // Ani Teacher profile banaune, User sanga link garera
        Teacher::create([
            'school_id' => $schoolId,
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
        ]);

        return redirect()->route('school-admin.teachers.index')
            ->with('status', 'Teacher successfully added. They can now log in with their email and password.');
    }

    public function show(Teacher $teacher): View
    {
        return view('school-admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        return view('school-admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
        ]);

        $teacher->update($validated);

        // Sanga sanga User account ko naam/email pani update garne
        if ($teacher->user) {
            $teacher->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('school-admin.teachers.index')
            ->with('status', 'Teacher successfully updated.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        // Teacher delete garda, sanga sangai unko login account pani delete huncha
        if ($teacher->user) {
            $teacher->user->delete();
        }

        $teacher->delete();

        return redirect()->route('school-admin.teachers.index')
            ->with('status', 'Teacher deleted.');
    }
}