<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Replace with your real data later
        return view('admin.reports.index', [
            'total_applicants' => 0,
            'approved' => 0,
            'rejected' => 0,
            'pending' => 0,
        ]);
    }

    public function evaluation()
    {
        return view('admin.reports.evaluation');
    }

    public function applicants()
    {
        return view('admin.reports.applicants');
    }

    public function scholars()
    {
        return view('admin.reports.scholars');
    }

    public function scoresheets()
    {
        return view('admin.reports.scoresheets');
    }
}

