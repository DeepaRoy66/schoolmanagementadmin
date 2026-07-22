<?php

namespace App\Http\Controllers;

use App\Models\ClassTeacherAssignment;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('classTeacherAssignment.schoolClass', 'classTeacherAssignment.section')
            ->orderBy('first_name')
            ->paginate(20);

        return view('school-admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('school-admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'middle_name'     => ['nullable', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'dob'             => ['nullable', 'date'],
            'gender'          => ['nullable', 'in:male,female,other'],
            'marital_status'  => ['nullable', 'in:single,married,other'],
            'pan_no'          => ['nullable', 'string', 'max:50'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email'],
            'address'         => ['nullable', 'string'],
            'designation'     => ['nullable', 'string', 'max:255'],
            'password'        => ['required', 'string', 'min:6'],
        ]);

        $schoolId = auth()->user()->school_id;

        DB::transaction(function () use ($validated, $schoolId) {
            $user = User::create([
                'name'      => trim($validated['first_name'] . ' ' . $validated['last_name']),
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role'      => 'teacher',
                'school_id' => $schoolId,
            ]);

            Teacher::create([
                'school_id'      => $schoolId,
                'user_id'        => $user->id,
                'first_name'     => $validated['first_name'],
                'middle_name'    => $validated['middle_name'] ?? null,
                'last_name'      => $validated['last_name'],
                'email'          => $validated['email'],
                'phone'          => $validated['phone'] ?? null,
                'dob'            => $validated['dob'] ?? null,
                'gender'         => $validated['gender'] ?? null,
                'marital_status' => $validated['marital_status'] ?? null,
                'pan_no'         => $validated['pan_no'] ?? null,
                'address'        => $validated['address'] ?? null,
                'designation'    => $validated['designation'] ?? null,
                'is_active'      => true,
            ]);
        });

        return redirect()
            ->route('school-admin.teachers.index')
            ->with('success', 'Teacher successfully thapiyo.');
    }

    public function show(Teacher $teacher)
    {
        return redirect()->route('school-admin.teachers.edit', $teacher);
    }

    public function edit(Teacher $teacher)
    {
        return view('school-admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'middle_name'     => ['nullable', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'dob'             => ['nullable', 'date'],
            'gender'          => ['nullable', 'in:male,female,other'],
            'marital_status'  => ['nullable', 'in:single,married,other'],
            'pan_no'          => ['nullable', 'string', 'max:50'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $teacher->user_id],
            'address'         => ['nullable', 'string'],
            'designation'     => ['nullable', 'string', 'max:255'],
            'password'        => ['nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($validated, $teacher) {
            $teacher->update([
                'first_name'     => $validated['first_name'],
                'middle_name'    => $validated['middle_name'] ?? null,
                'last_name'      => $validated['last_name'],
                'email'          => $validated['email'],
                'phone'          => $validated['phone'] ?? null,
                'dob'            => $validated['dob'] ?? null,
                'gender'         => $validated['gender'] ?? null,
                'marital_status' => $validated['marital_status'] ?? null,
                'pan_no'         => $validated['pan_no'] ?? null,
                'address'        => $validated['address'] ?? null,
                'designation'    => $validated['designation'] ?? null,
            ]);

            $userData = [
                'name'  => trim($validated['first_name'] . ' ' . $validated['last_name']),
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $teacher->user()->update($userData);
        });

        return redirect()
            ->route('school-admin.teachers.index')
            ->with('success', 'Teacher profile update bhayo.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->update(['is_active' => false]);

        return redirect()
            ->route('school-admin.teachers.index')
            ->with('success', 'Teacher deactivate bhayo.');
    }

    // -------------------------------
    // Assign Class Teacher
    // (attendance access per class-section, via class_teacher_assignments table)
    // -------------------------------

    public function assignClassTeacherForm()
    {
        $classes = SchoolClass::with('sections')->orderBy('name')->get();
        $teachers = Teacher::where('is_active', true)->orderBy('first_name')->get();
        $assignments = ClassTeacherAssignment::with(['schoolClass', 'section', 'teacher'])->get();

        return view('school-admin.class-teacher.form', compact('classes', 'teachers', 'assignments'));
    }

    public function assignClassTeacher(Request $request)
    {
        $validated = $request->validate([
            'class_id'   => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        // Euta class-section (class_id + section_id combo) ko euta matra class teacher
        // huna sakcha - naya teacher le purano lai replace garcha. section_id matra
        // use gareko xaina kina bhane sections classes bich share huncha (Class 10 -
        // Section A ra Class 3 - Section A duitai faraak assignment hun sakcha).
        ClassTeacherAssignment::updateOrCreate(
            [
                'class_id'   => $validated['class_id'],
                'section_id' => $validated['section_id'],
            ],
            [
                'teacher_id' => $validated['teacher_id'],
                'school_id'  => auth()->user()->school_id,
            ]
        );

        return redirect()
            ->route('school-admin.class-teacher.form')
            ->with('success', 'Class teacher assign bhayo.');
    }

    public function removeClassTeacher($id)
    {
        ClassTeacherAssignment::findOrFail($id)->delete();

        return redirect()
            ->route('school-admin.class-teacher.form')
            ->with('success', 'Class teacher remove bhayo.');
    }
}