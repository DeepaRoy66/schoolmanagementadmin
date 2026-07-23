<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolAdminController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SystemUsageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectAllocationController;

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

        // -------------------------------
        // System Usage
        // -------------------------------
        Route::get('system-usage', [SystemUsageController::class, 'index'])
            ->name('system-usage.index');
    });


// ===============================
// School Admin Routes
// ===============================
Route::middleware(['auth', 'role:school_admin', 'license'])
    ->prefix('school-admin')
    ->name('school-admin.')
    ->group(function () {

        Route::resource('teachers', TeacherController::class);

        // -------------------------------
        // Subject Allocation (Class-Section x Subject -> Teacher matrix)
        // Handled by SubjectAllocationController. Names below no longer
        // re-prepend "school-admin." since the group already adds that
        // prefix (the old duplicate prefix produced an invalid route name).
        // -------------------------------
        Route::get('/subject-allocations', [SubjectAllocationController::class, 'index'])
            ->name('subject-allocations.index');

        Route::get('/subject-allocations/create', [SubjectAllocationController::class, 'create'])
            ->name('subject-allocations.create');

        Route::post('/subject-allocations', [SubjectAllocationController::class, 'store'])
            ->name('subject-allocations.store');

        // -------------------------------
        // Assign Class Teacher
        // (attendance access per class-section)
        // -------------------------------
        Route::get('class-teacher', [TeacherController::class, 'assignClassTeacherForm'])
            ->name('class-teacher.form');

        Route::post('class-teacher', [TeacherController::class, 'assignClassTeacher'])
            ->name('class-teacher.store');

        Route::delete('class-teacher/{id}', [TeacherController::class, 'removeClassTeacher'])
            ->name('class-teacher.remove');

        Route::resource('students', StudentController::class);

        Route::resource('notices', NoticeController::class)
            ->except(['show', 'edit', 'update']);

        Route::get('announcements', [AnnouncementController::class, 'index'])
            ->name('announcements.index');

        Route::resource('timetables', TimetableController::class)
            ->except(['show', 'edit', 'update']);

        // -------------------------------
        // Fee Management
        // -------------------------------
        Route::get('fees/reports', [StudentFeeController::class, 'reports'])
            ->name('fees.reports');

        Route::resource('fee-categories', FeeCategoryController::class)
            ->except(['show']);

        Route::resource('student-fees', StudentFeeController::class)
            ->except(['show']);

        Route::resource('fee-payments', FeePaymentController::class)
            ->except(['show']);

        // -------------------------------
        // Reports & Subjects
        // -------------------------------
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::resource('subjects', SubjectController::class)->except(['show']);

        // -------------------------------
        // Classes & Sections
        // -------------------------------
        Route::resource('classes', ClassController::class)->except(['show', 'edit', 'update']);
        Route::resource('sections', SectionController::class)->except(['show', 'edit', 'update']);
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