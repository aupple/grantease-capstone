<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Routes
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});
