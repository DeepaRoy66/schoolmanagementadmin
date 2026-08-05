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

        
        $totalSchools  = School::count();
        $activeSchools = School::where('is_active', true)->count();
        $totalAdmins   = User::where('role', 'school_admin')->count();
        $trialSchools  = School::where('license_status', 'trial')->count();

        $recentSchools = School::latest()->take(5)->get();

        
        $schools = School::with('admins')
            ->latest()
            ->paginate(10, ['*'], 'schools_page');

        $schoolAdmins = User::where('role', 'school_admin')
            ->with('school')
            ->latest()
            ->paginate(10, ['*'], 'admins_page');

        
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