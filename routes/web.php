<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Optional default dashboard (you can remove if not used anymore)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 Authenticated routes
Route::middleware('auth')->group(function () {

    // 👤 User profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👨‍💼 Admin dashboard
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    // 👨‍🎓 Applicant dashboard
    Route::get('/applicant/dashboard', function () {
        return view('dashboard.applicant');
    })->name('applicant.dashboard');
});

require __DIR__.'/auth.php';
