<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
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

    // Role-based dashboard redirect
    Route::get('/dashboard', function () {
        $role = auth()->user()->role_id;

        return match ($role) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('applicant.dashboard'),
            default => abort(403, 'Unauthorized'),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/applications', 'admin.applications')->name('applications');
        Route::view('/scholars', 'admin.scholars')->name('scholars');
        Route::view('/reports', 'admin.reports')->name('reports');
        // Add settings or additional admin routes here
    });

    /*
    |--------------------------------------------------------------------------
    | Applicant Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('applicant')->as('applicant.')->group(function () {
        Route::view('/dashboard', 'applicant.dashboard')->name('dashboard');
        Route::view('/apply', 'applicant.apply')->name('apply');
        Route::view('/status', 'applicant.status')->name('status');

        // Application form submission
        Route::post('/apply', function (Request $request) {
            // Validate and handle submission logic
            // $validated = $request->validate([...]);
            return back()->with('status', 'Application submitted successfully!');
        })->name('apply.submit');
    });

    /*
    |--------------------------------------------------------------------------
    | Profile Routes (from Laravel Breeze)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze Auth (login, register, forgot password, etc.)
require __DIR__ . '/auth.php';
