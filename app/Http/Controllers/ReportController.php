<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Result;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(): View
    {
        $schoolId = auth()->user()->school_id;

        // Basic counts
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();

        // Attendance summary 
        $thisMonthAttendance = Attendance::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $totalAttendanceRecords = $thisMonthAttendance->count();
        $presentCount = $thisMonthAttendance->where('status', 'present')->count();
        $absentCount = $thisMonthAttendance->where('status', 'absent')->count();
        $lateCount = $thisMonthAttendance->where('status', 'late')->count();

        $attendancePercentage = $totalAttendanceRecords > 0
            ? round(($presentCount / $totalAttendanceRecords) * 100, 1)
            : 0;

        // Fee summary
        $totalFeeAmount = Fee::sum('amount');
        $totalCollected = Fee::sum('paid_amount');
        $totalPending = $totalFeeAmount - $totalCollected;
        $unpaidCount = Fee::where('status', 'unpaid')->count();

        // Results summary - class-wise average (simple overall average)
        $averageMarks = Result::selectRaw('AVG(marks_obtained / full_marks * 100) as avg_percentage')
            ->value('avg_percentage');

        $averageMarks = $averageMarks ? round($averageMarks, 1) : 0;

        // Class-wise student count
        $classCounts = Student::select('class', DB::raw('count(*) as total'))
            ->groupBy('class')
            ->orderBy('class')
            ->get();

        return view('school-admin.reports.index', compact(
            'totalTeachers',
            'totalStudents',
            'totalAttendanceRecords',
            'presentCount',
            'absentCount',
            'lateCount',
            'attendancePercentage',
            'totalFeeAmount',
            'totalCollected',
            'totalPending',
            'unpaidCount',
            'averageMarks',
            'classCounts'
        ));
    }
}