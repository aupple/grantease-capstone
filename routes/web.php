<?php

use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::redirect('/', '/login');

// ✅ Handle form submission (POST)
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Protected routes
Route::middleware(['auth'])->group(function () {
   Route::get('/applicant/application', [ApplicationFormController::class, 'create'])->name('applicant.application.create');
    Route::post('/applicant/application', [ApplicationFormController::class, 'store'])->name('applicant.application.store');
    // ✅ Smarter dashboard route redirects based on user role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role_id;

        if ($role == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 2) {
            return redirect()->route('applicant.dashboard');
        }

        abort(403); // Unknown or unauthorized role
    })->name('dashboard');

    Route::get('/applicant/dashboard', function () {
        return view('applicant.dashboard');
    })->name('applicant.dashboard');


Route::get('/admin/dashboard', function () {
    return view('admin.dashboard', [
        'total' => ApplicationForm::count(),
        'approved' => ApplicationForm::where('status', 'approved')->count(),
        'rejected' => ApplicationForm::where('status', 'rejected')->count(),
        'pending' => ApplicationForm::where('status', 'pending')->count(),
    ]);
})->name('admin.dashboard');

    Route::get('/admin/applications', [AdminController::class, 'viewApplications'])->name('admin.applications');
    Route::get('/admin/applications/{id}', [AdminController::class, 'showApplication'])->name('admin.applications.show');
    Route::post('/admin/applications/{id}/approve', [AdminController::class, 'approveApplication'])->name('admin.applications.approve');
    Route::post('/admin/applications/{id}/reject', [AdminController::class, 'rejectApplication'])->name('admin.applications.reject');
    Route::get('/admin/reports', [AdminController::class, 'reportSummary'])->name('admin.reports');
    Route::get('/admin/reports/pdf', [AdminController::class, 'downloadReportPdf'])->name('admin.reports.pdf');
    Route::get('/admin/scholars', [AdminController::class, 'viewScholars'])->name('admin.scholars');
    Route::get('/admin/applications', [AdminController::class, 'viewApplications'])->name('admin.applications');
 



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze routes
require __DIR__.'/auth.php';
