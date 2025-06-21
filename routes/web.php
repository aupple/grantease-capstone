<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect to correct dashboard
Route::get('/dashboard', [UserController::class, 'redirectToDashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

// Admin dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

// Applicant dashboard
Route::get('/applicant/dashboard', function () {
    return view('applicant.dashboard');
})->middleware(['auth'])->name('applicant.dashboard');