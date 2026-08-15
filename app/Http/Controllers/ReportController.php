<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentFee;
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

        $totalTeachers = Teacher::where('school_id', $schoolId)->count();
        $totalStudents = Student::where('school_id', $schoolId)->count();

        $thisMonthAttendance = Attendance::where('school_id', $schoolId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $totalAttendanceRecords = $thisMonthAttendance->count();
        $presentCount = $thisMonthAttendance->where('status', 'present')->count();
        $absentCount = $thisMonthAttendance->where('status', 'absent')->count();
        $lateCount = $thisMonthAttendance->where('status', 'late')->count();

        $attendancePercentage = $totalAttendanceRecords > 0
            ? round(($presentCount / $totalAttendanceRecords) * 100, 1)
            : 0;

        $totalFeeAmount = StudentFee::where('school_id', $schoolId)->sum('amount');
        $totalCollected = StudentFee::where('school_id', $schoolId)->sum('paid_amount');
        $totalPending = $totalFeeAmount - $totalCollected;
        $unpaidCount = StudentFee::where('school_id', $schoolId)->where('status', 'unpaid')->count();

        $averageMarks = Result::where('school_id', $schoolId)
            ->selectRaw('AVG(marks_obtained / full_marks * 100) as avg_percentage')
            ->value('avg_percentage');

        $averageMarks = $averageMarks ? round($averageMarks, 1) : 0;

        $classCounts = Student::select('classes.name as class', DB::raw('count(*) as total'))
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->where('students.school_id', $schoolId)
            ->groupBy('classes.name')
            ->orderBy('classes.name')
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