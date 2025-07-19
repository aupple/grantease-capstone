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

    // ✅ Applicant Routes
    Route::get('/applicant/application', [ApplicationFormController::class, 'create'])->name('applicant.application.create');
    Route::post('/applicant/application', [ApplicationFormController::class, 'store'])->name('applicant.application.store');

    Route::get('/applicant/dashboard', function () {
        return view('applicant.dashboard');
    })->name('applicant.dashboard');

    Route::get('/applicant/my-application', [ApplicationFormController::class, 'viewMyApplication'])->name('applicant.application.view');
    Route::get('/applicant/application/{id}/edit', [ApplicationFormController::class, 'edit'])->name('applicant.application.edit');
    Route::patch('/applicant/application/{id}', [ApplicationFormController::class, 'update'])->name('applicant.application.update');

    // ✅ Smarter role-based redirect
    Route::get('/dashboard', function () {
        $role = auth()->user()->role_id;

        if ($role == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 2) {
            return redirect()->route('applicant.dashboard');
        }

        abort(403); // Unknown or unauthorized role
    })->name('dashboard');

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Admin Application Management
    Route::get('/admin/applications', [AdminController::class, 'viewApplications'])->name('admin.applications');
    Route::get('/admin/applications/{id}', [AdminController::class, 'showApplication'])->name('admin.applications.show');
    Route::post('/admin/applications/{id}/approve', [AdminController::class, 'approveApplication'])->name('admin.applications.approve');
    Route::post('/admin/applications/{id}/reject', [AdminController::class, 'rejectApplication'])->name('admin.applications.reject');
    Route::post('/admin/applications/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.applications.update-status');
    Route::get('/admin/reports/pdf', [AdminController::class, 'downloadReportPdf'])->name('admin.reports.pdf');

    // Admin Reports & Scholars
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::post('/admin/reports/export-selected', [ReportController::class, 'exportSelected'])->name('admin.reports.export-selected');
    Route::get('/admin/reports/applicants', [ReportController::class, 'applicants'])->name('admin.reports.applicants');
    Route::get('/admin/reports/monitoring', [ReportController::class, 'monitoring'])->name('admin.reports.monitoring');
    Route::post('/admin/reports/monitoring/save', [ReportController::class, 'saveMonitoring'])->name('admin.reports.monitoring.save');
    Route::get('/admin/reports/monitoring/download', [ReportController::class, 'downloadMonitoring'])->name('admin.reports.monitoring.download');

    // Scholars list (from AdminController)
    Route::get('/admin/scholars', [AdminController::class, 'viewScholars'])->name('admin.scholars');

    // Profile Management for applicants
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile Management for admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    });

    // Applicant PDF Routes
    Route::prefix('applicant')->name('applicant.')->group(function () {
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

}); // <-- Close middleware(['auth'])

// Breeze Auth Routes
require __DIR__.'/auth.php';
