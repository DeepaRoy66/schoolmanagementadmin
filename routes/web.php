<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolAdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\TimetableController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'license'])->name('dashboard');

Route::get('/admin-only', function () {
    return 'Welcome Super Admin! Yo page tapai matra dekhna paunuhuncha.';
})->middleware(['auth', 'role:super_admin'])->name('admin.only');


// ===============================
// Super Admin Routes
// ===============================
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('schools', SchoolController::class);

        Route::resource('school-admins', SchoolAdminController::class);

        Route::post('schools/{school}/renew', [SchoolController::class, 'renew'])
            ->name('schools.renew');
    });


// ===============================
// School Admin Routes
// ===============================
Route::middleware(['auth', 'role:school_admin', 'license'])
    ->prefix('school-admin')
    ->name('school-admin.')
    ->group(function () {

        Route::resource('teachers', TeacherController::class);

        Route::resource('students', StudentController::class);

        Route::resource('notices', NoticeController::class)
            ->except(['show', 'edit', 'update']);

        Route::resource('timetables', TimetableController::class)
            ->except(['show', 'edit', 'update']);
    });


// ===============================
// Profile Routes
// ===============================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';