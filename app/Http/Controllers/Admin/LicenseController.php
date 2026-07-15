<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LicenseController extends Controller
{
    /**
     * All schools, sorted by license expiry (soonest first).
     */
    public function index(Request $request)
    {
        $schools = School::query()
            ->when($request->filled('status'), fn ($q) =>
                $q->where('license_status', $request->status)
            )
            ->orderByRaw('license_expiry IS NULL, license_expiry ASC')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all'     => School::count(),
            'active'  => School::where('license_status', 'active')->count(),
            'trial'   => School::where('license_status', 'trial')->count(),
            'expired' => School::where('license_status', 'expired')->count(),
        ];

        return view('admin.licenses.index', compact('schools', 'counts'));
    }

    /**
     * Schools whose license expires within the next 14 days (and aren't already expired).
     */
    public function expiring(Request $request)
    {
        $days = $request->integer('days', 14);

        $schools = School::query()
            ->where('license_status', '!=', 'expired')
            ->whereNotNull('license_expiry')
            ->whereBetween('license_expiry', [now(), now()->addDays($days)])
            ->orderBy('license_expiry')
            ->paginate(15)
            ->withQueryString();

        return view('admin.licenses.expiring', compact('schools', 'days'));
    }

    /**
     * Renew / extend a school's license.
     */
    public function renew(Request $request, School $school)
    {
        $validated = $request->validate([
            'license_expiry' => ['required', 'date', 'after:today'],
        ]);

        $school->update([
            'license_expiry' => $validated['license_expiry'],
            'license_status' => 'active',
        ]);

        return back()->with('status', "License renewed for {$school->name}.");
    }

    /**
     * Activate / deactivate a school's license.
     */
    public function toggleStatus(School $school)
    {
        $newStatus = $school->license_status === 'active' ? 'expired' : 'active';

        $school->update(['license_status' => $newStatus]);

        return back()->with('status', "License for {$school->name} set to " . ucfirst($newStatus) . ".");
    }
}