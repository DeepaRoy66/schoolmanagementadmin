<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolAdminController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\FeeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'license'])
    ->name('dashboard');

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

        // -------------------------------
        // Licenses
        // -------------------------------
        Route::get('licenses', [LicenseController::class, 'index'])
            ->name('licenses.index');

        Route::get('licenses/expiring', [LicenseController::class, 'expiring'])
            ->name('licenses.expiring');

        Route::post('licenses/{school}/renew', [LicenseController::class, 'renew'])
            ->name('licenses.renew');

        Route::patch('licenses/{school}/toggle', [LicenseController::class, 'toggleStatus'])
            ->name('licenses.toggle');

        // -------------------------------
        // Announcements
        // -------------------------------
        Route::resource('announcements', AnnouncementController::class)
            ->only(['index', 'create', 'store', 'destroy']);
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

        Route::resource('fees', FeeController::class)
            ->except(['show', 'edit', 'update']);

        Route::patch('fees/{fee}/payment', [FeeController::class, 'updatePayment'])
            ->name('fees.payment');
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