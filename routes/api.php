<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\HomeworkController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\NoticeController;
use Illuminate\Support\Facades\Route;

// Public - login garna kohi pani access garna sakcha
Route::post('/login', [AuthController::class, 'login']);

// Protected - token chahincha
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

    // Student endpoints
    Route::get('/student/attendance', [AttendanceController::class, 'myAttendance']);
    Route::get('/student/homework', [HomeworkController::class, 'myHomework']);
    Route::get('/student/results', [ResultController::class, 'myResults']);

    // Shared endpoint - Teacher ra Student duitai le use garne
    Route::get('/notices', [NoticeController::class, 'index']);
    Route::get('/timetable', [TimetableController::class, 'index']);
});