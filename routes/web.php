<?php

use Illuminate\Support\Facades\Route;

// -------------------------------
// Public Routes (Login/Register)
// -------------------------------
Route::view('/', 'login')->name('login');
Route::view('/login', 'login');
Route::view('/register', 'register');
Route::view('/verify', 'verify');
Route::view('/forgot', 'forgot')->name('forgot');

