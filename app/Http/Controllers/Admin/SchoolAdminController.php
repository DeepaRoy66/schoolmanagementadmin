<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SchoolAdminController extends Controller
{

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $schoolAdmins = User::where('role', 'school_admin')
            ->with('school')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.school-admins.index', compact('schoolAdmins'));
    }

    public function create(): View
    {
        $schools = School::orderBy('name')->get();

        return view('admin.school-admins.create', compact('schools'));
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'school_id' => $validated['school_id'],
            'role' => 'school_admin',
            'email_verified_at' => now(),
            'must_change_password' => false,
        ]);

        return redirect()->route('admin.school-admins.index')
            ->with('status', 'School Admin successfully created.');
    }


    public function show(User $school_admin): View
    {
        return view('admin.school-admins.show', ['schoolAdmin' => $school_admin]);
    }


    public function edit(User $school_admin): View
    {
        $schools = School::orderBy('name')->get();

        return view('admin.school-admins.edit', [
            'schoolAdmin' => $school_admin,
            'schools' => $schools,
        ]);
    }

    /**
     * Edit gareko data update garne
     */
    public function update(Request $request, User $school_admin): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $school_admin->id,
            'password' => 'nullable|string|min:8',
            'school_id' => 'required|exists:schools,id',
        ]);

        $school_admin->name = $validated['name'];
        $school_admin->email = $validated['email'];
        $school_admin->school_id = $validated['school_id'];

        if (!empty($validated['password'])) {
            $school_admin->password = Hash::make($validated['password']);
        }

        $school_admin->save();

        return redirect()->route('admin.school-admins.index')
            ->with('status', 'School Admin successfully updated.');
    }

    /**
     * School admin delete garne
     */
    public function destroy(User $school_admin): RedirectResponse
    {
        $school_admin->delete();

        return redirect()->route('admin.school-admins.index')
            ->with('status', 'School Admin deleted.');
    }
}