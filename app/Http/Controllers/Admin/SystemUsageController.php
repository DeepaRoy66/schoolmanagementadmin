<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Support\Carbon;

class SystemUsageController extends Controller
{
    public function index()
    {
        // --- Top-line counts ---
        $stats = [
            'total_schools'   => School::count(),
            'active_licenses' => School::where('license_status', 'active')->count(),
            'trial_licenses'  => School::where('license_status', 'trial')->count(),
            'expired_licenses'=> School::where('license_status', 'expired')->count(),
            'total_admins'    => User::where('role', 'school_admin')->count(),
            'total_teachers'  => User::where('role', 'teacher')->count(),
            'total_students'  => User::where('role', 'student')->count(),
            'announcements'   => Announcement::count(),
        ];

        // --- License status distribution (for donut chart) ---
        $licenseDistribution = [
            'Active'  => $stats['active_licenses'],
            'Trial'   => $stats['trial_licenses'],
            'Expired' => $stats['expired_licenses'],
        ];

        // --- Schools added per month, last 6 months (for bar chart) ---
        $schoolGrowth = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'label' => $date->format('M Y'),
                'count' => School::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        // --- Most recently active schools (based on any user's last_login_at) ---
        $recentlyActive = School::query()
            ->withMax('users as last_activity', 'last_login_at')
            ->having('last_activity', '!=', null)
            ->orderByDesc('last_activity')
            ->take(5)
            ->get();

        // --- Inactive schools: no user has logged in within 30 days (or ever) ---
        $inactiveSchools = School::query()
            ->withMax('users as last_activity', 'last_login_at')
            ->having(function ($query) {
                $query->havingNull('last_activity')
                    ->orHavingRaw('last_activity < ?', [now()->subDays(30)]);
            })
            ->orderBy('last_activity')
            ->take(5)
            ->get();

        return view('admin.system-usage.index', compact(
            'stats',
            'licenseDistribution',
            'schoolGrowth',
            'recentlyActive',
            'inactiveSchools'
        ));
    }
}