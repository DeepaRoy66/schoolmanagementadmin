<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::with(['schoolClass', 'section', 'academicYear'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('middle_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('roll_number', 'like', '%' . $search . '%')
                      ->orWhere('student_uid', 'like', '%' . $search . '%')
                      ->orWhereRaw(
                          "CONCAT_WS(' ', first_name, NULLIF(middle_name, ''), last_name) LIKE ?",
                          ['%' . $search . '%']
                      );
                });
            })
            ->when($request->filled('academic_year_id'), function ($query) use ($request) {
                $query->where('academic_year_id', $request->academic_year_id);
            })
            ->when($request->filled('class_id'), function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })
            ->when($request->filled('section_id'), function ($query) use ($request) {
                $query->where('section_id', $request->section_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();

        return view('school-admin.students.index', compact('students', 'classes', 'sections', 'academicYears'));
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::with('classes')->orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();

        return view('school-admin.students.create', compact('classes', 'sections', 'academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'required|string|email|max:255|unique:students,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'telephone_no' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'local_guardian_name' => 'nullable|string|max:255',
            'local_guardian_phone' => 'nullable|string|max:20',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,dropped_out',
            'password' => 'required|string|min:8',
        ]);

        $schoolId = auth()->user()->school_id;
        $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students/photos', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $schoolId, $fullName, $photoPath) {
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
                    'academic_year_id' => $validated['academic_year_id'] ?? null,
                    'user_id' => $user->id,
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'last_name' => $validated['last_name'],
                    'dob' => $validated['dob'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'student_uid' => Student::generateStudentUid($schoolId),
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'photo' => $photoPath,
                    'parent_name' => $validated['parent_name'] ?? null,
                    'parent_phone' => $validated['parent_phone'] ?? null,
                    'telephone_no' => $validated['telephone_no'] ?? null,
                    'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                    'emergency_contact_relation' => $validated['emergency_contact_relation'] ?? null,
                    'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                    'mother_name' => $validated['mother_name'] ?? null,
                    'mother_phone' => $validated['mother_phone'] ?? null,
                    'father_name' => $validated['father_name'] ?? null,
                    'father_phone' => $validated['father_phone'] ?? null,
                    'local_guardian_name' => $validated['local_guardian_name'] ?? null,
                    'local_guardian_phone' => $validated['local_guardian_phone'] ?? null,
                    'class_id' => $validated['class_id'] ?? null,
                    'section_id' => $validated['section_id'] ?? null,
                    'roll_number' => $validated['roll_number'] ?? null,
                    'status' => $validated['status'],
                    'is_active' => $validated['status'] === 'active',
                ]);
            });
        } catch (\Exception $e) {
            // Clean up the uploaded photo if the transaction failed,
            // so we don't leave orphaned files on disk.
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Could not add student: ' . $e->getMessage());
        }

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
        $sections = Section::with('classes')->orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();

        return view('school-admin.students.edit', compact('student', 'classes', 'sections', 'academicYears'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_photo' => 'nullable|boolean',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'telephone_no' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'local_guardian_name' => 'nullable|string|max:255',
            'local_guardian_phone' => 'nullable|string|max:20',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,dropped_out',
            'password' => 'nullable|string|min:8',
        ]);

        $validated['is_active'] = $validated['status'] === 'active';

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = null;
        } else {
            unset($validated['photo']);
        }
        unset($validated['remove_photo']);

        // Password is only for the linked User account, not a Student column —
        // pull it out before updating the student and only touch it if provided.
        $newPassword = $validated['password'] ?? null;
        unset($validated['password']);

        $student->update($validated);

        if ($student->user) {
            $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);
            $userUpdate = [
                'name' => $fullName,
                'email' => $validated['email'],
            ];

            if (!empty($newPassword)) {
                $userUpdate['password'] = Hash::make($newPassword);
            }

            $student->user->update($userUpdate);
        }

        return redirect()->route('school-admin.students.index')
            ->with('status', 'Student successfully updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        return redirect()->route('school-admin.students.index')
            ->with('status', 'Student deleted.');
    }
}