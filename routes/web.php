<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolAdminController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SystemUsageController;
use App\Http\Controllers\Admin\HealthReportController;
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
use App\Http\Controllers\BillingPeriodController;
use App\Http\Controllers\FeeGroupController;
use App\Http\Controllers\FeeNameController;
use App\Http\Controllers\FeeRateController;
use App\Http\Controllers\FeeDiscountController;
use App\Http\Controllers\FeeAssignController;
use App\Http\Controllers\TimetableImageController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AcademicYearRunController;
use App\Http\Controllers\ClassChangeController;
use App\Http\Controllers\Admin\FeedbackController;

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

        // -------------------------------
        // Feedback (moved here from school-admin group — super_admin only)
        // -------------------------------
        Route::get('feedback', [FeedbackController::class, 'index'])
            ->name('feedback.index');

        Route::patch('feedback/{feedback}/status', [FeedbackController::class, 'updateStatus'])
            ->name('feedback.update-status');

        Route::delete('feedback/{feedback}', [FeedbackController::class, 'destroy'])
            ->name('feedback.destroy');
    });


// ===============================
// School Admin Routes
// ===============================
Route::middleware(['auth', 'role:school_admin', 'license'])
    ->prefix('school-admin')
    ->name('school-admin.')
    ->group(function () {

        Route::resource('teachers', TeacherController::class);


        Route::get('/subject-allocations', [SubjectAllocationController::class, 'index'])
            ->name('subject-allocations.index');

        Route::get('/subject-allocations/create', [SubjectAllocationController::class, 'create'])
            ->name('subject-allocations.create');

        Route::post('/subject-allocations', [SubjectAllocationController::class, 'store'])
            ->name('subject-allocations.store');


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



        Route::resource('timetable-images', TimetableImageController::class)
            ->except(['show', 'edit', 'update']);

        Route::get('timetable-images/{classId}/{sectionId}/history', [TimetableImageController::class, 'history'])
            ->name('timetable-images.history');

        Route::resource('billing-periods', BillingPeriodController::class);


        Route::get('fees/reports', [StudentFeeController::class, 'reports'])
            ->name('fees.reports');


        Route::resource('fee-categories', FeeCategoryController::class)
            ->except(['show']);

        Route::resource('student-fees', StudentFeeController::class)
            ->except(['show']);

        Route::resource('fee-payments', FeePaymentController::class)
            ->except(['show']);

        Route::resource('fee-groups', FeeGroupController::class)
            ->except(['show']);

        Route::resource('fee-names', FeeNameController::class);

        Route::resource('fee-rates', FeeRateController::class)->except(['show']);


        Route::get('fee-discounts/create', [FeeDiscountController::class, 'create'])
            ->name('fee-discounts.create');

        Route::post('fee-discounts', [FeeDiscountController::class, 'store'])
            ->name('fee-discounts.store');

        Route::get('fee-discounts/sections', [FeeDiscountController::class, 'sections'])
            ->name('fee-discounts.sections');

        Route::get('fee-discounts/students', [FeeDiscountController::class, 'students'])
            ->name('fee-discounts.students');

        Route::get('fee-discounts/fee-rows', [FeeDiscountController::class, 'feeRows'])
            ->name('fee-discounts.fee-rows');


        Route::get('fee-assign', [FeeAssignController::class, 'index'])
            ->name('fee-assign.index');

        Route::get('fee-assign/create', [FeeAssignController::class, 'create'])
            ->name('fee-assign.create');

        Route::post('fee-assign', [FeeAssignController::class, 'store'])
            ->name('fee-assign.store');

        Route::post('fee-assign/bulk-void', [FeeAssignController::class, 'bulkVoid'])
            ->name('fee-assign.bulk-void');

        Route::get('fee-assign/invoice', [FeeAssignController::class, 'invoice'])
            ->name('fee-assign.invoice');

        Route::get('fee-payments/{student}/pay', [FeePaymentController::class, 'payFeeForm'])
            ->name('fee-payments.pay-form');

        Route::get('fee-payments/receipt/{paymentGroup}', [FeePaymentController::class, 'receipt'])
            ->name('fee-payments.receipt');

        // -------------------------------
        // Reports & Subjects
        // -------------------------------
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::resource('subjects', SubjectController::class)->except(['show']);

        // -------------------------------
        // Health Reports
        // -------------------------------
        Route::get('health-reports', [HealthReportController::class, 'index'])
            ->name('health-reports.index');

        Route::patch('health-reports/{healthReport}/status', [HealthReportController::class, 'updateStatus'])
            ->name('health-reports.update-status');

        Route::delete('health-reports/{healthReport}', [HealthReportController::class, 'destroy'])
            ->name('health-reports.destroy');

        // -------------------------------
        // Academic Year
        // -------------------------------
        Route::resource('academic-years', AcademicYearController::class)
            ->except(['show']);

        Route::resource('academic-year-runs', AcademicYearRunController::class)
            ->except(['show']);

        Route::get('class-change', [ClassChangeController::class, 'index'])
            ->name('class-change.index');

        Route::post('class-change', [ClassChangeController::class, 'update'])
            ->name('class-change.update');

        // -------------------------------
        // Classes & Sections
        // -------------------------------
        Route::resource('classes', ClassController::class)->except(['show']);
        Route::resource('sections', SectionController::class)->except(['show']);
    });



Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';