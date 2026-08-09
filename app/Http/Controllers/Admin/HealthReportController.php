<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthReport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class HealthReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HealthReport::with(['student', 'schoolClass', 'reporter'])
            ->where('school_id', $user->school_id)
            ->latest();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->query('class_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('school-admin.health-reports.index', compact('reports'));
    }

    public function updateStatus(Request $request, HealthReport $healthReport): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
        ]);

        $healthReport->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function destroy(HealthReport $healthReport): RedirectResponse
    {
        if ($healthReport->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($healthReport->photo_path);
        }

        $healthReport->delete();

        return back()->with('success', 'Health report deleted.');
    }
}