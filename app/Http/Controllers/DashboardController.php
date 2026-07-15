<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Notice;
use App\Models\Fee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'school_admin') {
            return $this->schoolAdminDashboard($user);
        }

        // Default: super_admin dashboard
        // Cheap COUNT queries for the stat cards — never load full tables into memory just to count them.
        $totalSchools  = School::count();
        $activeSchools = School::where('is_active', true)->count();
        $totalAdmins   = User::where('role', 'school_admin')->count();
        $trialSchools  = School::where('license_status', 'trial')->count();

        // Small, fixed-size query for the dashboard's "recent schools" widget.
        $recentSchools = School::latest()->take(5)->get();

        // Paginated + eager-loaded so the Schools table stays fast as data grows
        // and doesn't trigger an N+1 query when showing each school's admin.
        $schools = School::with('admins')
            ->latest()
            ->paginate(10, ['*'], 'schools_page');

        $schoolAdmins = User::where('role', 'school_admin')
            ->with('school')
            ->latest()
            ->paginate(10, ['*'], 'admins_page');

        // Full, unpaginated list for the Add Admin form's school dropdown.
        $allSchools = School::orderBy('name')->get(['id', 'name']);

        return view('dashboard', compact(
            'totalSchools',
            'activeSchools',
            'totalAdmins',
            'trialSchools',
            'recentSchools',
            'schools',
            'schoolAdmins',
            'allSchools'
        ));
    }

    protected function schoolAdminDashboard(User $user)
    {
        $schoolId = $user->school_id;

        // Cheap COUNT/SUM queries scoped to this admin's own school only.
        $totalStudents      = Student::where('school_id', $schoolId)->count();
        $totalTeachers      = Teacher::where('school_id', $schoolId)->count();
        $totalNotices       = Notice::where('school_id', $schoolId)->count();
        $totalFeesCollected = Fee::where('school_id', $schoolId)
            ->where('status', 'paid')
            ->sum('amount');

        return view('dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalNotices',
            'totalFeesCollected'
        ));
    }
}