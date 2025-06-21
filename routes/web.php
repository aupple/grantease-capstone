<?php

use Illuminate\Support\Facades\Route;

// -------------------------------
// Public Routes (Login/Register)
// -------------------------------
Route::view('/', 'authentication.login')->name('login');
Route::view('/login', 'authentication.login');
Route::view('/register', 'authentication.register');
Route::view('/verify', 'authentication.verify');
Route::view('/forgot', 'authentication.forgot')->name('forgot');


// -------------------------------
// Applicant Routes
// -------------------------------
Route::prefix('applicant')->group(function () {
    Route::view('/home', 'applicant.home')->name('applicant.home');
    Route::view('/application', 'applicant.application')->name('applicant.application');
    Route::view('/application/status', 'applicant.status')->name('applicant.status');
});

// -------------------------------
// Admin Routes
// -------------------------------
Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/applications', 'admin.applications')->name('admin.applications');
    Route::view('/review/{id}', 'admin.review')->name('admin.review');
});
