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

    // ✅ View the logged-in applicant's submitted applications
    public function viewMyApplication()
    {
        $applications = ApplicationForm::where('user_id', Auth::id())->latest()->get();
        return view('applicant.my-application', compact('applications'));
    }

    // ✅ Show edit form for "submitted" applications
    public function edit($id)
    {
        $application = ApplicationForm::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'submitted') // updated from 'pending'
            ->firstOrFail();

        return view('applicant.application-edit', compact('application'));
    }

    // ✅ Handle update of "submitted" applications
    public function update(Request $request, $id)
    {
        $application = ApplicationForm::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'submitted') // updated from 'pending'
            ->firstOrFail();

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

        // Check if user already applied for this program
        $existing = ApplicationForm::where('user_id', Auth::id())
            ->where('program', $request->program)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already applied for the ' . $request->program . ' program.');
        }

        // Save new application
        ApplicationForm::create([
            'user_id' => Auth::id(),
            'program' => $request->program,
            'school' => $request->school,
            'year_level' => $request->year_level,
            'reason' => $request->reason,
            'status' => 'submitted', // ✅ updated from 'pending'
            'submitted_at' => now(),
        ]);

        return redirect()->route('applicant.dashboard')
            ->with('success', 'Application submitted successfully!');
    }
}
