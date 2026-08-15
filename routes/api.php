<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\HomeworkController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\TeacherDashboardController;
use App\Http\Controllers\Api\TimetableImageController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\CalendarEventController;
use App\Http\Controllers\Api\HealthReportController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\StudentEmergencyContactController;
use App\Http\Controllers\Api\TeacherEmergencyContactController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Teacher endpoints
    Route::get('/teacher/students', [AttendanceController::class, 'students']);
    Route::post('/teacher/attendance', [AttendanceController::class, 'markAttendance']);
    Route::get('/teacher/attendance', [AttendanceController::class, 'viewByDate']);
    Route::get('/teacher/homework', [HomeworkController::class, 'index']);
    Route::post('/teacher/homework', [HomeworkController::class, 'store']);
    Route::delete('/teacher/homework/{homework}', [HomeworkController::class, 'destroy']);
    Route::post('/teacher/results', [ResultController::class, 'store']);
    Route::get('/teacher/results', [ResultController::class, 'viewByExam']);
    Route::get('/teacher/materials', [MaterialController::class, 'index']);
    Route::post('/teacher/materials', [MaterialController::class, 'store']);
    Route::delete('/teacher/materials/{material}', [MaterialController::class, 'destroy']);
    Route::get('/teacher/dashboard-summary', [TeacherDashboardController::class, 'summary']);
    Route::get('/teacher/total-classes', [TeacherDashboardController::class, 'totalClasses']); 
    Route::get('/teacher/assigned-classes', [AttendanceController::class, 'assignedClasses']); 
    Route::get('/teacher/sections', [AttendanceController::class, 'sections']);

    // Teacher: student emergency contacts (Mother/Father/Local Guardian/Own number)
    Route::get('/teacher/students/emergency-contacts', [TeacherEmergencyContactController::class, 'index']);

    // Teacher: health reports (view + status update)
    Route::get('/teacher/health-reports', [HealthReportController::class, 'index']);
    Route::patch('/teacher/health-reports/{healthReport}/status', [HealthReportController::class, 'updateStatus']);

    // Feedback (merged: student + teacher both submit/view own via same endpoint,
    // controller checks $user->role internally)
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::get('/feedback', [FeedbackController::class, 'index']);

    // Super Admin only: view all feedback (across roles, all schools) + update status
    Route::get('/admin/feedback', [FeedbackController::class, 'adminIndex']);
    Route::get('/admin/feedback/{feedback}', [FeedbackController::class, 'adminShow']);
    Route::patch('/admin/feedback/{feedback}/status', [FeedbackController::class, 'updateStatus']);

    // Student endpoints
    Route::get('/student/attendance', [AttendanceController::class, 'myAttendance']);
    Route::get('/student/attendance/summary', [AttendanceController::class, 'myAttendanceSummary']);
    Route::get('/student/homework', [HomeworkController::class, 'myHomework']);
    Route::post('/student/homework/{homework}/complete', [HomeworkController::class, 'markComplete']);
    Route::get('/student/results', [ResultController::class, 'myResults']);
    Route::get('/student/fees', [FeeController::class, 'myFees']);
    Route::get('/student/materials', [MaterialController::class, 'myMaterials']);
    Route::get('/student/teachers', [AttendanceController::class, 'myTeachers']);

    // Student: health reports (submit + view own)
    Route::post('/student/health-reports', [HealthReportController::class, 'store']);
    Route::get('/student/health-reports', [HealthReportController::class, 'index']);
    Route::get('/student/health-reports/{healthReport}', [HealthReportController::class, 'show']);

    // School Admin: health reports (view all + status update + delete)
    Route::get('/admin/health-reports', [HealthReportController::class, 'index']);
    Route::get('/admin/health-reports/{healthReport}', [HealthReportController::class, 'show']);
    Route::patch('/admin/health-reports/{healthReport}/status', [HealthReportController::class, 'updateStatus']);
    Route::delete('/admin/health-reports/{healthReport}', [HealthReportController::class, 'destroy']);



    // Shared endpoints
    Route::get('/notices', [NoticeController::class, 'index']);
    Route::get('/timetable', [TimetableImageController::class, 'show']);
    Route::get('/gallery', [GalleryController::class, 'index']);
    Route::post('/gallery', [GalleryController::class, 'store']);
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy']);

    // Calendar events (holidays / exams / school events)
    Route::get('/calendar-events', [CalendarEventController::class, 'index']);
    Route::post('/calendar-events', [CalendarEventController::class, 'store']);
    Route::put('/calendar-events/{calendarEvent}', [CalendarEventController::class, 'update']);
    Route::delete('/calendar-events/{calendarEvent}', [CalendarEventController::class, 'destroy']);

  
    Route::get('/student/emergency-contacts', [StudentEmergencyContactController::class, 'index']);
});