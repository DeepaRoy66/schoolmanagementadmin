<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\HomeworkController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\TeacherDashboardController;
use App\Http\Controllers\Api\TimetableController;
use App\Http\Controllers\Api\MaterialController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

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
    Route::get('/teacher/total-classes', [TeacherDashboardController::class, 'totalClasses']); // naya

    // Student endpoints
    Route::get('/student/attendance', [AttendanceController::class, 'myAttendance']);
    Route::get('/student/attendance/summary', [AttendanceController::class, 'myAttendanceSummary']); // naya
    Route::get('/student/homework', [HomeworkController::class, 'myHomework']);
    Route::get('/student/results', [ResultController::class, 'myResults']);
    Route::get('/student/fees', [FeeController::class, 'myFees']);
    Route::get('/student/fees/summary', [FeeController::class, 'summary']); // naya
    Route::get('/student/materials', [MaterialController::class, 'myMaterials']);

    // Shared endpoints
    Route::get('/notices', [NoticeController::class, 'index']);
    Route::get('/timetable', [TimetableController::class, 'index']);
});