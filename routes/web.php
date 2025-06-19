<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// -------------------------------
// Public Routes (Login/Register)
// -------------------------------
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Forgot Password Routes
Route::get('/forgot', [AuthController::class, 'showForgotForm'])->name('forgot.form');
Route::post('/forgot', [AuthController::class, 'submitForgot'])->name('forgot');

