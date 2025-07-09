<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
        $role = auth()->Auth::user()()->role_id;

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

    // Admin Reports & Scholars
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/admin/reports/pdf', [AdminController::class, 'downloadReportPdf'])->name('admin.reports.pdf');
    Route::post('/admin/reports/export-selected', [ReportController::class, 'exportSelected'])->name('admin.reports.export-selected');

    //Evaluation Sheet routes (index and show)
    Route::get('/admin/reports/evaluation/{id}', [ReportController::class, 'evaluationShow'])->name('admin.reports.evaluation.show');
    Route::get('/admin/reports/evaluation', [ReportController::class, 'evaluation'])->name('admin.reports.evaluation');
    Route::post('/admin/reports/evaluation/{id}/update', [ReportController::class, 'evaluationUpdate'])->name('admin.reports.evaluation.update');
    Route::post('/admin/reports/evaluation/{id}/save', [ReportController::class, 'evaluationSave'])->name('admin.reports.evaluation.save');
    
    // Scorescheets
    Route::get('/admin/reports/scoresheets', [ReportController::class, 'scoresheets'])->name('admin.reports.scoresheets');
    Route::get('/admin/reports/scoresheets/{id}', [ReportController::class, 'scoresheetShow'])->name('admin.reports.scoresheet.show');



    // Other Report Pages
    Route::get('/admin/reports/applicants', [ReportController::class, 'applicants'])->name('admin.reports.applicants');
    Route::get('/admin/reports/scholars', [ReportController::class, 'scholars'])->name('admin.reports.scholars');
    Route::get('/admin/reports/scoresheets', [ReportController::class, 'scoresheets'])->name('admin.reports.scoresheets');

    // Scholars list (from AdminController)
    Route::get('/admin/scholars', [AdminController::class, 'viewScholars'])->name('admin.scholars');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes
require __DIR__ . '/auth.php';
