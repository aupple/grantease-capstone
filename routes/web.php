<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\PdfController;

Route::redirect('/', '/login');

// ✅ Handle form submission (POST)
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Protected routes
Route::middleware(['auth'])->group(function () {

    /**
     * =======================
     * Applicant Routes
     * =======================
     */
    Route::prefix('applicant')->name('applicant.')->group(function () {
        Route::get('/application/{program}', [ApplicationFormController::class, 'create'])->name('application.create');
        Route::post('/applicant/application', [ApplicationFormController::class, 'store'])->name('application.store');
        Route::get('/dashboard', fn () => view('applicant.dashboard'))->name('dashboard');
        Route::get('/my-application', [ApplicationFormController::class, 'viewMyApplication'])->name('application.view');
        Route::get('/application/{id}/edit', [ApplicationFormController::class, 'edit'])->name('application.edit');
        Route::patch('/application/{id}', [ApplicationFormController::class, 'update'])->name('application.update');

        // Applicant PDF Routes
        Route::prefix('pdf')->name('pdf.')->group(function () {
            Route::get('/health-certificate', [PdfController::class, 'healthCertificate'])->name('health_certificate');
            Route::get('/score-sheet', [PdfController::class, 'scoreSheet'])->name('score_sheet');
            Route::get('/recommendation-form', [PdfController::class, 'recommendationForm'])->name('recommendation_form');
            Route::get('/research-plans', [PdfController::class, 'researchPlans'])->name('research_plans');
            Route::get('/career-plans', [PdfController::class, 'careerPlans'])->name('career_plans');
            Route::get('/certification-employment', [PdfController::class, 'certificationEmployment'])->name('certification_employment');
            Route::get('/certification-deped', [PdfController::class, 'certificationDepEd'])->name('certification_deped');
            Route::get('/certification-health-status', [PdfController::class, 'certificationHealthStatus'])->name('certification_health_status');
        });
    });

    /**
     * =======================
     * Role-based redirect
     * =======================
     */
    Route::get('/dashboard', function () {
        $role = auth()->user()->role_id;

        if ($role == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 2) {
            return redirect()->route('applicant.dashboard');
        }

        abort(403); // Unknown or unauthorized role
    })->name('dashboard');

    /**
     * =======================
     * Admin Routes
     * =======================
     */
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Applications
        Route::get('/applications', [AdminController::class, 'viewApplications'])->name('applications');
        Route::get('/applications/{id}', [AdminController::class, 'showApplication'])->name('applications.show');
        Route::post('/applications/{id}/approve', [AdminController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{id}/reject', [AdminController::class, 'rejectApplication'])->name('applications.reject');
        Route::post('/applications/{id}/status', [AdminController::class, 'updateStatus'])->name('applications.update-status');

        // ✅ Rejected Applications
        Route::prefix('rejected')->name('rejected.')->group(function () {
            Route::get('/', [AdminController::class, 'rejectedApplications'])->name('index');
            Route::get('/{id}', [AdminController::class, 'showRejected'])->name('show');
     });

         // Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/pdf', [AdminController::class, 'downloadReportPdf'])->name('reports.pdf');
Route::post('/reports/export-selected', [ReportController::class, 'exportSelected'])->name('reports.export-selected');
Route::get('/reports/applicants', [ReportController::class, 'applicants'])->name('reports.applicants');
Route::post('/reports/applicants/save', [ReportController::class, 'saveApplicants'])->name('reports.applicants.save');
Route::get('/reports/applicants/print', [ReportController::class, 'printAllApplicants'])->name('reports.applicants.print'); 
Route::get('/reports/monitoring', [ReportController::class, 'monitoring'])->name('reports.monitoring');
Route::post('/reports/monitoring/save', [ReportController::class, 'saveMonitoring'])->name('reports.monitoring.save');
Route::post('/reports/applicants/update-field', [ReportController::class, 'updateField'])->name('reports.applicants.update-field');
Route::get('/reports/monitoring/download', [ReportController::class, 'downloadMonitoring'])->name('reports.monitoring.download');
Route::get('/reports/monitoring/print', [ReportController::class, 'printMonitoring'])->name('reports.monitoring.print');
Route::get('/reports/ched-monitoring', [ReportController::class, 'chedmonitoring'])->name('reports.ched-monitoring');
Route::get('/reports/monitoring/{id}', [ReportController::class, 'showMonitoring'])->name('reports.monitoring.show');

        // Scholars
        Route::get('/scholars', [AdminController::class, 'viewScholars'])->name('scholars');
        // Scholar Monitoring
Route::prefix('monitoring')->name('monitoring.')->group(function () {
    Route::get('/', [App\Http\Controllers\ScholarMonitoringController::class, 'index'])->name('index');
    Route::get('/{scholar}/create', [App\Http\Controllers\ScholarMonitoringController::class, 'create'])->name('create');
    Route::post('/{scholar}', [App\Http\Controllers\ScholarMonitoringController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [App\Http\Controllers\ScholarMonitoringController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\ScholarMonitoringController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\ScholarMonitoringController::class, 'destroy'])->name('destroy');
});


        // Admin Profile
        Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    });
        Route::get('/application/{id}/edit', [ApplicationFormController::class, 'edit'])
    ->name('applicant.application-edit');

    Route::patch('/application/{id}', [ApplicationFormController::class, 'update'])
    ->name('application.update');

    /**
     * =======================
     * Profile Management for Applicants
     * =======================
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes
require __DIR__ . '/auth.php';
