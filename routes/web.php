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
use App\Http\Controllers\PeriodTimetableController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\GalleryController;


// ===============================
// Home
// ===============================

Route::get('/', function () {
    return redirect()->route('login');
});


// ===============================
// Dashboard
// ===============================

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'license'])
    ->name('dashboard');


// ===============================
// Super Admin Only
// ===============================

Route::get('/admin-only', function () {
    return 'Welcome Super Admin! Yo page tapai matra dekhna paunuhuncha.';
})
    ->middleware(['auth', 'role:super_admin'])
    ->name('admin.only');


// ===============================
// Super Admin Routes
// ===============================

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // -------------------------------
        // Schools
        // -------------------------------

        Route::resource('schools', SchoolController::class);

        // -------------------------------
        // School Admins
        // -------------------------------

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
        // Feedback
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

        // -------------------------------
        // Teachers
        // -------------------------------

        Route::resource('teachers', TeacherController::class);

        // -------------------------------
        // Subject Allocations
        // -------------------------------

        Route::get('/subject-allocations', [SubjectAllocationController::class, 'index'])
            ->name('subject-allocations.index');

        Route::post('/subject-allocations', [SubjectAllocationController::class, 'store'])
            ->name('subject-allocations.store');

        Route::delete('/subject-allocations/{allocation}', [SubjectAllocationController::class, 'destroy'])
            ->name('subject-allocations.destroy');

        // -------------------------------
        // Class Teacher
        // -------------------------------

        Route::get('class-teacher', [TeacherController::class, 'assignClassTeacherForm'])
            ->name('class-teacher.form');

        Route::post('class-teacher', [TeacherController::class, 'assignClassTeacher'])
            ->name('class-teacher.store');

        Route::delete('class-teacher/{id}', [TeacherController::class, 'removeClassTeacher'])
            ->name('class-teacher.remove');

        // -------------------------------
        // Students
        // -------------------------------

        Route::resource('students', StudentController::class);

        // -------------------------------
        // Notices
        // -------------------------------

        Route::resource('notices', NoticeController::class)
            ->except(['show', 'edit', 'update']);

        // -------------------------------
        // Announcements
        // -------------------------------

        Route::get('announcements', [AnnouncementController::class, 'index'])
            ->name('announcements.index');

        // -------------------------------
        // Timetables
        // -------------------------------

        Route::resource('timetables', TimetableController::class)
            ->except(['show', 'edit', 'update']);

        // -------------------------------
        // Timetable Images
        // -------------------------------

        Route::resource('timetable-images', TimetableImageController::class)
            ->except(['show', 'edit', 'update']);

        Route::get(
            'timetable-images/{classId}/{sectionId}/history',
            [TimetableImageController::class, 'history']
        )
            ->name('timetable-images.history');

        // -------------------------------
        // Billing Periods
        // -------------------------------

        Route::resource('billing-periods', BillingPeriodController::class);

        // -------------------------------
        // Fee Reports
        // -------------------------------

        Route::get('fees/reports', [StudentFeeController::class, 'reports'])
            ->name('fees.reports');

        // -------------------------------
        // Fee Categories
        // -------------------------------

        Route::resource('fee-categories', FeeCategoryController::class)
            ->except(['show']);

        // -------------------------------
        // Student Fees
        // -------------------------------

        Route::resource('student-fees', StudentFeeController::class)
            ->except(['show']);

        // -------------------------------
        // Fee Payments
        // -------------------------------

        Route::resource('fee-payments', FeePaymentController::class)
            ->except(['show']);

        // -------------------------------
        // Fee Groups
        // -------------------------------

        Route::resource('fee-groups', FeeGroupController::class)
            ->except(['show']);

        // -------------------------------
        // Fee Names
        // -------------------------------

        Route::resource('fee-names', FeeNameController::class);

        // -------------------------------
        // Fee Rates
        // -------------------------------

        Route::resource('fee-rates', FeeRateController::class)
            ->except(['show']);

        // -------------------------------
        // Fee Discounts
        // -------------------------------

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

        // -------------------------------
        // Fee Assign
        // -------------------------------

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

        // -------------------------------
        // Fee Payment Custom Routes
        // -------------------------------

        Route::get(
            'fee-payments/{student}/pay',
            [FeePaymentController::class, 'payFeeForm']
        )
            ->name('fee-payments.pay-form');

        Route::post(
            'fee-payments/{student}/pay',
            [FeePaymentController::class, 'payStore']
        )
            ->name('fee-payments.pay-store');

        Route::get(
            'fee-payments/{student}/statement',
            [FeePaymentController::class, 'statement']
        )
            ->name('fee-payments.statement');

        Route::get(
            'fee-payments/{student}/fine-waive',
            [FeePaymentController::class, 'fineWaiveForm']
        )
            ->name('fee-payments.fine-waive');

        Route::get(
            'fee-payments/receipt/{paymentGroup}',
            [FeePaymentController::class, 'receipt']
        )
            ->name('fee-payments.receipt');

        // -------------------------------
        // Reports
        // -------------------------------

        Route::get('reports', [ReportController::class, 'index'])
            ->name('reports.index');

        // -------------------------------
        // Subjects
        // -------------------------------

        Route::resource('subjects', SubjectController::class)
            ->except(['show']);

        // -------------------------------
        // Health Reports
        // -------------------------------

        Route::get('health-reports', [HealthReportController::class, 'index'])
            ->name('health-reports.index');

        Route::get('health-reports/{healthReport}', [HealthReportController::class, 'show'])
            ->name('health-reports.show');

        Route::patch(
            'health-reports/{healthReport}/status',
            [HealthReportController::class, 'updateStatus']
        )
            ->name('health-reports.update-status');

        Route::delete(
            'health-reports/{healthReport}',
            [HealthReportController::class, 'destroy']
        )
            ->name('health-reports.destroy');

        // -------------------------------
        // Academic Year
        // -------------------------------

        Route::resource('academic-years', AcademicYearController::class)
            ->except(['show']);

        Route::resource('academic-year-runs', AcademicYearRunController::class)
            ->except(['show']);

        // -------------------------------
        // Class Change
        // -------------------------------

        Route::get('class-change', [ClassChangeController::class, 'index'])
            ->name('class-change.index');

        Route::post('class-change', [ClassChangeController::class, 'update'])
            ->name('class-change.update');

        // -------------------------------
        // Classes
        // -------------------------------

        Route::resource('classes', ClassController::class)
            ->except(['show']);

        // -------------------------------
        // Sections
        // -------------------------------

        Route::resource('sections', SectionController::class)
            ->except(['show']);

        // -------------------------------
        // Period Timetable
        // -------------------------------

        Route::resource('period-timetable', PeriodTimetableController::class)
            ->except(['create', 'show']);

        // -------------------------------
        // Emergency Contacts
        // -------------------------------

        Route::resource('emergency-contacts', EmergencyContactController::class);

        // -------------------------------
        // Calendar Events
        // -------------------------------

        Route::resource('calendar-events', CalendarEventController::class);

        // -------------------------------
        // Gallery
        // View + Delete
        // Upload happens from Teacher/Student App
        // -------------------------------

        Route::get('gallery', [GalleryController::class, 'index'])
            ->name('gallery.index');

        Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])
            ->name('gallery.destroy');
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


// ===============================
// Authentication Routes
// ===============================

require __DIR__.'/auth.php';