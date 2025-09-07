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
        // Validate program
        if (!in_array($program, ['DOST', 'CHED'])) {
            abort(404); // If invalid program, show 404
        }

        // Pass the program to your Blade view
        return view('applicant.application-form', compact('program'));
    }

    /**
     * Store a newly created application form in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic info
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'school_term' => 'nullable|string|max:255',
            'application_no' => 'nullable|string|max:255',
            'program' => 'required|string|in:DOST,CHED',

            // Contact / personal details
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'civil_status' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',

            // Academic background
            'bs_field' => 'nullable|string|max:255',
            'bs_university' => 'nullable|string|max:255',
            'bs_scholarship_type' => 'nullable|string|max:255',
            'bs_scholarship_others' => 'nullable|string|max:255',
            'bs_remarks' => 'nullable|string|max:500',

            // Graduate intent
            'grad_field' => 'nullable|string|max:255',
            'grad_university' => 'nullable|string|max:255',
            'grad_plan' => 'nullable|string|max:255',

            // Employment
            'employer_name' => 'nullable|string|max:255',
            'employer_address' => 'nullable|string|max:500',
            'position' => 'nullable|string|max:255',
            'employment_status' => 'nullable|string|max:255',

            // Research & plans
            'research_title' => 'nullable|string|max:500',
            'career_plan' => 'nullable|string|max:500',

            // File uploads
            'passport_picture' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'form137' => 'nullable|file|mimes:pdf|max:4096',
            'cert_employment' => 'nullable|file|mimes:pdf|max:4096',
            'cert_purpose' => 'nullable|file|mimes:pdf|max:4096',

            'birth_certificate_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'transcript_of_record_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'endorsement_1_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'endorsement_2_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'recommendation_head_agency_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'form_2a_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'form_2b_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'form_a_research_plans_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'form_b_career_plans_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'form_c_health_status_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'nbi_clearance_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'letter_of_admission_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'approved_program_of_study_pdf' => 'nullable|file|mimes:pdf|max:4096',
            'lateral_certification_pdf' => 'nullable|file|mimes:pdf|max:4096',

            // Declaration
            'terms_and_conditions_agreed' => 'nullable|boolean',
            'applicant_signature' => 'nullable|string|max:255',
            'declaration_date' => 'nullable|date',
        ]);

        $application = new ApplicationForm();
        $application->user_id = Auth::id();
        $application->program = $request->program;
        $application->status = 'pending';
        $application->submitted_at = now();

        // Fill non-file fields
        $application->fill(collect($validated)->except([
            'passport_picture',
            'form137',
            'cert_employment',
            'cert_purpose',
            'birth_certificate_pdf',
            'transcript_of_record_pdf',
            'endorsement_1_pdf',
            'endorsement_2_pdf',
            'recommendation_head_agency_pdf',
            'form_2a_pdf',
            'form_2b_pdf',
            'form_a_research_plans_pdf',
            'form_b_career_plans_pdf',
            'form_c_health_status_pdf',
            'nbi_clearance_pdf',
            'letter_of_admission_pdf',
            'approved_program_of_study_pdf',
            'lateral_certification_pdf',
        ])->toArray());

        // File uploads
        $fileFields = [
            'passport_picture',
            'form137',
            'cert_employment',
            'cert_purpose',
            'birth_certificate_pdf',
            'transcript_of_record_pdf',
            'endorsement_1_pdf',
            'endorsement_2_pdf',
            'recommendation_head_agency_pdf',
            'form_2a_pdf',
            'form_2b_pdf',
            'form_a_research_plans_pdf',
            'form_b_career_plans_pdf',
            'form_c_health_status_pdf',
            'nbi_clearance_pdf',
            'letter_of_admission_pdf',
            'approved_program_of_study_pdf',
            'lateral_certification_pdf',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store("uploads/application_forms", "public");
                $application->$field = $path;
            }
        }

        $application->save();

        return redirect()->route('dashboard')
            ->with('success', 'Application form submitted successfully.');
    }

    /**
     * Update an existing application form.
     */
    public function update(Request $request, $id)
    {
        $application = ApplicationForm::findOrFail($id);

        // Ensure only the owner can update
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'program' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'reason' => 'nullable|string|max:1000',
            // file fields
            'passport_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'form137' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'cert_employment' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'cert_purpose' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        // Update normal fields
        $application->program = $validated['program'];
        $application->school = $validated['school'];
        $application->year_level = $validated['year_level'];
        $application->reason = $validated['reason'] ?? $application->reason;

        // Handle file uploads (optional replacement)
        if ($request->hasFile('passport_picture')) {
            $application->passport_picture = $request->file('passport_picture')->store('uploads/passport', 'public');
        }

        if ($request->hasFile('form137')) {
            $application->form137 = $request->file('form137')->store('uploads/form137', 'public');
        }

        if ($request->hasFile('cert_employment')) {
            $application->cert_employment = $request->file('cert_employment')->store('uploads/employment', 'public');
        }

        if ($request->hasFile('cert_purpose')) {
            $application->cert_purpose = $request->file('cert_purpose')->store('uploads/purpose', 'public');
        }

        // Keep status "pending" after edit
        $application->status = 'pending';
        $application->save();

        return redirect()->route('applicant.myApplication')
            ->with('success', 'Your application has been updated and set to Pending.');
    }

    /**
     * Show all applications submitted by the logged-in user.
     */
    public function viewMyApplication()
    {
        $applications = auth()->user()->applicationForms()->latest()->get();
        return view('applicant.my-application', compact('applications'));
    }

    public function edit($id)
    {
        $application = ApplicationForm::findOrFail($id);

        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('applicant.application-edit', compact('application'));
    }
}
