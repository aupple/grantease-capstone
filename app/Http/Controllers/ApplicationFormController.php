<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationFormController extends Controller
{
    /**
     * Show the application form for applicants.
     */
    public function create($program)
    {
        // ✅ Validate program type
        if (!in_array($program, ['DOST', 'CHED'])) {
            abort(404, 'Invalid program.');
        }

        return view('applicant.application-form', compact('program'));
    }

    /**
     * Display all applications for the logged-in user.
     */
    public function viewMyApplication()
    {
        $user = Auth::user();
        $applications = ApplicationForm::where('user_id', $user->user_id)->get();

        return view('applicant.my-application', compact('applications'));
    }

    /**
     * Store a newly created application form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // I. Basic Info
            'academic_year' => 'required|string|max:20',
            'school_term' => 'required|string|max:20',
            'application_no' => 'required|string|max:50',
            'passport_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',

            // II. Personal Info
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'address_no' => 'nullable|string|max:50',
            'address_street' => 'nullable|string|max:150',
            'barangay' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'passport_no' => 'nullable|string|max:50',
            'email_address' => 'required|email|max:150',
            'current_address' => 'nullable|string',
            'telephone_nos' => 'nullable|string|max:50',
            'alternate_contact' => 'nullable|string|max:50',
            'civil_status' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer',
            'sex' => 'nullable|string|max:10',
            'father_name' => 'nullable|string|max:150',
            'mother_name' => 'nullable|string|max:150',

            // III. Educational Background
            'bs_period' => 'nullable|string|max:50',
            'bs_field' => 'nullable|string|max:100',
            'bs_university' => 'nullable|string|max:150',
            'bs_scholarship_type' => 'nullable|string|max:100',
            'bs_remarks' => 'nullable|string|max:150',

            'ms_period' => 'nullable|string|max:50',
            'ms_field' => 'nullable|string|max:100',
            'ms_university' => 'nullable|string|max:150',
            'ms_scholarship_type' => 'nullable|string|max:100',
            'ms_remarks' => 'nullable|string|max:150',

            'phd_period' => 'nullable|string|max:50',
            'phd_field' => 'nullable|string|max:100',
            'phd_university' => 'nullable|string|max:150',
            'phd_scholarship_type' => 'nullable|string|max:100',
            'phd_remarks' => 'nullable|string|max:150',

            // IV. Graduate Scholarship Intentions
            'strand_category' => 'nullable|string|max:50',
            'applicant_type' => 'nullable|string|max:50',
            'scholarship_type' => 'nullable|string|max:50',
            'new_applicant_university' => 'nullable|string|max:150',
            'new_applicant_course' => 'nullable|string|max:150',
            'lateral_university_enrolled' => 'nullable|string|max:150',
            'lateral_course_degree' => 'nullable|string|max:150',
            'lateral_units_earned' => 'nullable|string|max:50',
            'lateral_remaining_units' => 'nullable|string|max:50',

            // VIII. Declaration
            'applicant_signature' => 'nullable|string|max:150',
            'declaration_date' => 'nullable|date',
        ]);

        // ✅ Create new application record
        $application = new ApplicationForm();
        $application->user_id = Auth::user()->user_id;
        $application->program = $request->program;
        $application->status = 'pending';
        $application->submitted_at = now();

        // ✅ Fill validated fields
        $application->fill($validated);

        // ✅ Handle passport photo upload
        if ($request->hasFile('passport_picture')) {
            $path = $request->file('passport_picture')->store('uploads/application_photos', 'public');
            $application->photo = $path;
        }

        // ✅ Save to database
        $application->save();

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }

    /**
     * Edit existing application.
     */
    public function edit($id)
    {
        $application = ApplicationForm::findOrFail($id);

        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('applicant.application-edit', compact('application'));
    }

    /**
     * Update existing application (AJAX save).
     */
    public function update(Request $request, $id)
    {
        $application = ApplicationForm::findOrFail($id);

        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $application->update($request->all());

        return response()->json(['success' => true]);
    }
}
