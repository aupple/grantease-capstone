<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;

// ✅ Show register form (GET)
Route::get('/', [RegisteredUserController::class, 'create'])->name('register');

// ✅ Handle form submission (POST)
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/applicant/dashboard', function () {
        return view('dashboard.applicant');
    })->name('applicant.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze routes
require __DIR__.'/auth.php';
