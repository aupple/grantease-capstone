<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;

class ApplicationFormController extends Controller
{
    // ✅ Show the application form
    public function create(Request $request)
    {
        $program = $request->input('program'); // "DOST" or "CHED"
        return view('applicant.application-form', compact('program'));
    }

    // ✅ View the logged-in applicant's submitted application
    public function viewMyApplication()
    {
        $application = ApplicationForm::where('user_id', Auth::id())->latest()->first();
        return view('applicant.my-application', compact('application'));
    }

    // ✅ Show edit form for pending application
    public function edit()
    {
        $application = ApplicationForm::where('user_id', Auth::id())
                        ->where('status', 'pending')
                        ->first();

        if (!$application) {
            return redirect()->route('applicant.application.view')
                ->with('error', 'Only pending applications can be edited.');
        }

        return view('applicant.application-edit', compact('application'));
    }

    // ✅ Handle update of pending application
    public function update(Request $request)
    {
        $application = ApplicationForm::where('user_id', Auth::id())
                        ->where('status', 'pending')
                        ->first();

        if (!$application) {
            return redirect()->route('applicant.application.view')
                ->with('error', 'Only pending applications can be updated.');
        }

        $request->validate([
            'school' => 'required|string',
            'year_level' => 'required|string',
            'reason' => 'required|string',
        ]);

        $application->update([
            'school' => $request->school,
            'year_level' => $request->year_level,
            'reason' => $request->reason,
        ]);

        return redirect()->route('applicant.dashboard')
            ->with('success', 'Application updated successfully!');
    }

    // ✅ Handle new application submission
    public function store(Request $request)
    {
        $request->validate([
            'program' => 'required|string',
            'school' => 'required|string',
            'year_level' => 'required|string',
            'reason' => 'required|string',
        ]);

        $application = new ApplicationForm();
        $application->user_id = Auth::id();
        $application->program = $request->program;
        $application->school = $request->school;
        $application->year_level = $request->year_level;
        $application->reason = $request->reason;
        $application->status = 'pending';
        $application->submitted_at = now();
        $application->save();

        return redirect()->route('applicant.dashboard')
            ->with('success', 'Application submitted successfully!');
    }
}
