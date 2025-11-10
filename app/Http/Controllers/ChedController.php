<?php

namespace App\Http\Controllers;

class ChedController extends Controller
{
    // Show CHED dashboard
    public function index()
    {
        return view('ched.dashboard');
    }
}
