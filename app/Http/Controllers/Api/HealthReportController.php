<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthReport;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class HealthReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = HealthReport::with(['student:id,first_name,middle_name,last_name', 'schoolClass:id,name', 'reporter:id,name'])
            ->where('school_id', $user->school_id)
            ->latest();

        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                $query->where('student_id', $student->id);
            }
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->query('class_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $reports = $query->paginate(20);

        return response()->json($reports);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'You are not authorized to submit a health report.'], 403);
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student record not found.'], 404);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('health_reports', 'public');
        }

        $report = HealthReport::create([
            'school_id' => $user->school_id,
            'class_id' => $student->class_id,
            'student_id' => $student->id,
            'reported_by' => $user->id,
            'message' => $validated['message'],
            'photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Health report submitted successfully.',
            'report' => $report,
        ], 201);
    }

    public function show(Request $request, HealthReport $healthReport): JsonResponse
    {
        $user = $request->user();

        if ($healthReport->school_id !== $user->school_id) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student || $healthReport->student_id !== $student->id) {
                return response()->json(['message' => 'You are not authorized to view this report.'], 403);
            }
        }

        return response()->json($healthReport->load(['student:id,first_name,middle_name,last_name', 'schoolClass:id,name', 'reporter:id,name']));
    }

    public function updateStatus(Request $request, HealthReport $healthReport): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'school_admin' && $user->role !== 'teacher') {
            return response()->json(['message' => 'You are not authorized to update this report.'], 403);
        }

        if ($healthReport->school_id !== $user->school_id) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
        ]);

        $healthReport->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Status updated successfully.',
            'report' => $healthReport,
        ]);
    }

    public function destroy(Request $request, HealthReport $healthReport): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'school_admin' && $healthReport->reported_by !== $user->id) {
            return response()->json(['message' => 'You are not authorized to delete this report.'], 403);
        }

        if ($healthReport->photo_path) {
            Storage::disk('public')->delete($healthReport->photo_path);
        }

        $healthReport->delete();

        return response()->json(['message' => 'Health report deleted successfully.']);
    }
}