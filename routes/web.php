<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::redirect('/', '/login');

// Breeze registration route
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role_id;

        return match ($role) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('applicant.dashboard'),
            default => abort(403, 'Unauthorized access'),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Applications
        Route::get('/applications', [AdminController::class, 'viewApplications'])->name('applications');
        Route::get('/applications/{id}', [AdminController::class, 'showApplication'])->name('applications.show');
        Route::post('/applications/{id}/approve', [AdminController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{id}/reject', [AdminController::class, 'rejectApplication'])->name('applications.reject');

<<<<<<< HEAD
        // Reports
        Route::get('/reports', [AdminController::class, 'reportSummary'])->name('reports');
        Route::get('/reports/pdf', [AdminController::class, 'downloadReportPdf'])->name('reports.download');
=======
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
 
>>>>>>> d65b1dac2147ef244807d26b5537693ed11f0791

        // Scholars
        Route::get('/scholars', [AdminController::class, 'viewScholars'])->name('scholars');
    });

    /*
    |--------------------------------------------------------------------------
    | Applicant Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('applicant')->as('applicant.')->group(function () {
        Route::view('/dashboard', 'applicant.dashboard')->name('dashboard');
        Route::get('/apply', [ApplicationFormController::class, 'create'])->name('application.create'); // ✅ this fixes the error
        Route::post('/apply', [ApplicationFormController::class, 'store'])->name('application.store'); // ⬅ handle submission
        Route::view('/status', 'applicant.status')->name('status');
    });

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes from Breeze (login, forgot password, etc.)
require __DIR__ . '/auth.php';
