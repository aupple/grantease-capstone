<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;

class ApplicationFormController extends Controller
{
    // Show the application form
    public function create(Request $request)
    {
         $program = $request->input('program'); // "DOST" or "CHED"
    return view('applicant.application-form', compact('program'));
    }

    // Handle form submission
    public function store(Request $request)
    {
        
        $request->validate([
            'program' => 'required|string',
            'school' => 'required|string',
            'year_level' => 'required|string',
            'reason' => 'required|string',
        ]);

        $application = new ApplicationForm();
        $application->user_id = Auth::id(); // logged-in applicant
        $application->program = $request->program;
        $application->school = $request->school;
        $application->year_level = $request->year_level;
        $application->reason = $request->reason;
        $application->status = 'pending';
        $application->submitted_at = now(); // if you have this column
        $application->save();

        return redirect()->route('applicant.dashboard')->with('success', 'Application submitted successfully!');
    }
}
