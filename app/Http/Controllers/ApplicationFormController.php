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
        ->where('status', 'submitted') // Only editable if still submitted
        ->firstOrFail();

    // ✅ Validate input
    $validated = $request->validate([
        'program' => 'required|string',
        'school' => 'required|string',
        'year_level' => 'required|string',
        'reason' => 'required|string',

        // Personal info
        'permanent_address' => 'nullable|string',
        'zip_code' => 'nullable|string',
        'region' => 'nullable|string',
        'district' => 'nullable|string',
        'passport_no' => 'nullable|string',
        'current_address' => 'nullable|string',
        'civil_status' => 'nullable|string',
        'birthdate' => 'nullable|date',
        'age' => 'nullable|string',
        'sex' => 'nullable|string',
        'father_name' => 'nullable|string',
        'mother_name' => 'nullable|string',
        'phone_number' => 'nullable|string',

        // Academic info
        'bs_field' => 'nullable|string',
        'bs_school' => 'nullable|string',
        'bs_scholarship' => 'nullable|string',
        'bs_remarks' => 'nullable|string',
        'ms_field' => 'nullable|string',
        'ms_school' => 'nullable|string',
        'ms_scholarship' => 'nullable|string',
        'ms_remarks' => 'nullable|string',
        'phd_field' => 'nullable|string',
        'phd_school' => 'nullable|string',
        'phd_scholarship' => 'nullable|string',
        'phd_remarks' => 'nullable|string',
        'strand_category' => 'nullable|string',
        'strand_type' => 'nullable|string',
        'scholarship_type' => 'nullable|string',
        'new_university' => 'nullable|string',
        'new_course' => 'nullable|string',
        'lateral_university' => 'nullable|string',
        'lateral_course' => 'nullable|string',
        'units_earned' => 'nullable|string',
        'units_remaining' => 'nullable|string',
        'research_approved' => 'nullable|string',
        'research_title' => 'nullable|string',
        'last_thesis_date' => 'nullable|string',

        // Employment
        'employment_status' => 'nullable|string',
        'position' => 'nullable|string',
        'length_of_service' => 'nullable|string',
        'company_name' => 'nullable|string',
        'company_address' => 'nullable|string',
        'company_email' => 'nullable|string',
        'company_website' => 'nullable|string',
        'company_phone' => 'nullable|string',
        'company_fax' => 'nullable|string',
        'business_name' => 'nullable|string',
        'business_address' => 'nullable|string',
        'business_email' => 'nullable|string',
        'business_type' => 'nullable|string',
        'years_operation' => 'nullable|string',
        'research_plans' => 'nullable|string',
        'career_plans' => 'nullable|string',
        'rnd_involvement' => 'nullable|string',

        // Arrays
        'publications' => 'nullable|array',
        'awards' => 'nullable|array',

        // File uploads
        'passport_picture' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'form137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cert_employment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cert_purpose' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // ✅ Prepare data
    $data = $validated;
    $data['publications'] = json_encode($request->input('publications'));
    $data['awards'] = json_encode($request->input('awards'));

    // ✅ Handle file re-uploads
    if ($request->hasFile('passport_picture')) {
        $data['passport_picture'] = $request->file('passport_picture')->store('passport_pictures', 'public');
    }
    if ($request->hasFile('form137')) {
        $data['form137'] = $request->file('form137')->store('form137_files', 'public');
    }
    if ($request->hasFile('cert_employment')) {
        $data['cert_employment'] = $request->file('cert_employment')->store('cert_employment_files', 'public');
    }
    if ($request->hasFile('cert_purpose')) {
        $data['cert_purpose'] = $request->file('cert_purpose')->store('cert_purpose_files', 'public');
    }

    // ✅ Update record
    $application->update($data);

    return redirect()->route('applicant.dashboard')
        ->with('success', 'Application updated successfully!');
}


    // ✅ Handle new application submission
public function store(Request $request)
{
    // ✅ Validate all fields
    $validated = $request->validate([
        // Required basics
        'program' => 'required|string',
        'school' => 'required|string',
        'year_level' => 'required|string',
        'reason' => 'required|string',

        // Personal info
        'permanent_address' => 'nullable|string',
        'zip_code' => 'nullable|string',
        'region' => 'nullable|string',
        'district' => 'nullable|string',
        'passport_no' => 'nullable|string',
        'current_address' => 'nullable|string',
        'civil_status' => 'nullable|string',
        'birthdate' => 'nullable|date',
        'age' => 'nullable|string',
        'sex' => 'nullable|string',
        'father_name' => 'nullable|string',
        'mother_name' => 'nullable|string',
        'phone_number' => 'nullable|string',

        // Academic info
        'bs_field' => 'nullable|string',
        'bs_school' => 'nullable|string',
        'bs_scholarship' => 'nullable|string',
        'bs_remarks' => 'nullable|string',
        'ms_field' => 'nullable|string',
        'ms_school' => 'nullable|string',
        'ms_scholarship' => 'nullable|string',
        'ms_remarks' => 'nullable|string',
        'phd_field' => 'nullable|string',
        'phd_school' => 'nullable|string',
        'phd_scholarship' => 'nullable|string',
        'phd_remarks' => 'nullable|string',
        'strand_category' => 'nullable|string',
        'strand_type' => 'nullable|string',
        'scholarship_type' => 'nullable|string',
        'new_university' => 'nullable|string',
        'new_course' => 'nullable|string',
        'lateral_university' => 'nullable|string',
        'lateral_course' => 'nullable|string',
        'units_earned' => 'nullable|string',
        'units_remaining' => 'nullable|string',
        'research_approved' => 'nullable|string',
        'research_title' => 'nullable|string',
        'last_thesis_date' => 'nullable|string',

        // Employment & R&D
        'employment_status' => 'nullable|string',
        'position' => 'nullable|string',
        'length_of_service' => 'nullable|string',
        'company_name' => 'nullable|string',
        'company_address' => 'nullable|string',
        'company_email' => 'nullable|string',
        'company_website' => 'nullable|string',
        'company_phone' => 'nullable|string',
        'company_fax' => 'nullable|string',
        'business_name' => 'nullable|string',
        'business_address' => 'nullable|string',
        'business_email' => 'nullable|string',
        'business_type' => 'nullable|string',
        'years_operation' => 'nullable|string',
        'research_plans' => 'nullable|string',
        'career_plans' => 'nullable|string',
        'rnd_involvement' => 'nullable|string',

        // Array fields
        'publications' => 'nullable|array',
        'awards' => 'nullable|array',

        // File uploads
        'passport_picture' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'form137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cert_employment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cert_purpose' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // ✅ Start building application data
    $data = $validated;
    $data['user_id'] = Auth::id();
    $data['status'] = 'pending';
    $data['submitted_at'] = now();

    // ✅ Encode arrays (convert to JSON for DB)
    $data['publications'] = json_encode($request->input('publications'));
    $data['awards'] = json_encode($request->input('awards'));

    // ✅ File uploads
    if ($request->hasFile('passport_picture')) {
        $data['passport_picture'] = $request->file('passport_picture')->store('passport_pictures', 'public');
    }
    if ($request->hasFile('form137')) {
        $data['form137'] = $request->file('form137')->store('form137_files', 'public');
    }
    if ($request->hasFile('cert_employment')) {
        $data['cert_employment'] = $request->file('cert_employment')->store('cert_employment_files', 'public');
    }
    if ($request->hasFile('cert_purpose')) {
        $data['cert_purpose'] = $request->file('cert_purpose')->store('cert_purpose_files', 'public');
    }

    // ✅ Save the application
    ApplicationForm::create($data);

    return redirect()->route('applicant.dashboard')->with('success', 'Application submitted successfully!');
}

}
