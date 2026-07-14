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
    /**
     * Sabai school admin haru list garne
     */
    public function index(): View
    {
        $schoolAdmins = User::where('role', 'school_admin')
            ->with('school')
            ->latest()
            ->paginate(10);

        return view('admin.school-admins.index', compact('schoolAdmins'));
    }

    /**
     * Naya school admin add garne form dekhaune
     */
    public function create(): View
    {
        $schools = School::orderBy('name')->get();

        return view('admin.school-admins.create', compact('schools'));
    }

    /**
     * Form bata aayeko data database ma save garne
     */
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
        ]);

        return redirect()->route('admin.school-admins.index')
            ->with('status', 'School Admin successfully created.');
    }

    /**
     * Euta school admin ko detail herne
     */
    public function show(User $school_admin): View
    {
        return view('admin.school-admins.show', ['schoolAdmin' => $school_admin]);
    }

    /**
     * School admin edit garne form dekhaune
     */
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