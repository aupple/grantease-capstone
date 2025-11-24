<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationForm;
use Illuminate\Support\Facades\Auth;
use App\Models\Remark;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;
use App\Services\TwilioService;

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
     * Show the current applicant's submitted application.
     */
   public function viewMyApplication()
{
    ActivityLogger::log('VIEW_APPLICATION', 'Viewed own application');
    
    $user = Auth::user();

    $applications = ApplicationForm::with(['remarks.attachments'])
        ->where('user_id', $user->user_id)
        ->get();

    // Load JSON files from storage/app/ directory
    $barangays = json_decode(file_get_contents(storage_path('app/psgc_barangay.json')), true);
    $cities = json_decode(file_get_contents(storage_path('app/psgc_city.json')), true);
    $provinces = json_decode(file_get_contents(storage_path('app/psgc_province.json')), true);

    return view('applicant.my-application', compact('applications', 'barangays', 'cities', 'provinces'));
}
    /**
     * Store a newly created application form in storage.
*/
public function store(Request $request)
{ 
    // Full validation rules
    $validated = $request->validate([
        // Step 1: Basic Info
        'academic_year' => 'required|string|max:20',
        'school_term' => 'required|string|max:20',
        'application_no' => 'required|string|max:50',
        'passport_picture' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',

        // Step 2: Personal Information
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
        'current_mailing_address' => 'nullable|string',
        'telephone_nos' => 'nullable|string|max:50',
        'civil_status' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'age' => 'nullable|integer',
        'sex' => 'nullable|string|max:10',
        'father_name' => 'nullable|string|max:150',
        'mother_name' => 'nullable|string|max:150',

        // Step 3: Educational Background
        'bs_degree' => 'nullable|string|max:100',
        'bs_period' => 'nullable|string|max:50',
        'bs_field' => 'nullable|string|max:100',
        'bs_university' => 'nullable|string|max:150',
        'bs_scholarship_type' => 'nullable|array',
        'bs_scholarship_others' => 'nullable|string|max:100',
        'bs_remarks' => 'nullable|string|max:150',

        'ms_degree' => 'nullable|string|max:100',
        'ms_period' => 'nullable|string|max:50',
        'ms_field' => 'nullable|string|max:100',
        'ms_university' => 'nullable|string|max:150',
        'ms_scholarship_type' => 'nullable|array',
        'ms_scholarship_others' => 'nullable|string|max:100',
        'ms_remarks' => 'nullable|string|max:150',

        'phd_degree' => 'nullable|string|max:100',
        'phd_period' => 'nullable|string|max:50',
        'phd_field' => 'nullable|string|max:100',
        'phd_university' => 'nullable|string|max:150',
        'phd_scholarship_type' => 'nullable|array',
        'phd_scholarship_others' => 'nullable|string|max:100',
        'phd_remarks' => 'nullable|string|max:150',

        // Step 4: Graduate Scholarship Intentions
        'strand_category' => 'nullable|string|max:50',
        'applicant_type' => 'nullable|string|max:50',
        'scholarship_type' => 'nullable|array',
        'new_applicant_university' => 'nullable|string|max:150',
        'new_applicant_course' => 'nullable|string|max:150',
        'lateral_university_enrolled' => 'nullable|string|max:150',
        'lateral_course_degree' => 'nullable|string|max:150',
        'lateral_units_earned' => 'nullable|integer',
        'lateral_remaining_units' => 'nullable|integer',
        'research_topic_approved' => 'nullable|boolean',
        'research_title' => 'nullable|string|max:200',
        'last_enrollment_date' => 'nullable|date',

        // Step 5: Employment
        'employment_status' => 'nullable|string|max:50',
        'employed_position' => 'nullable|string|max:150',
        'employed_length_of_service' => 'nullable|string|max:50',
        'employed_company_name' => 'nullable|string|max:150',
        'employed_company_address' => 'nullable|string',
        'employed_email' => 'nullable|string|max:150',
        'employed_website' => 'nullable|string|max:150',
        'employed_telephone' => 'nullable|string|max:50',
        'employed_fax' => 'nullable|string|max:50',
        'self_employed_business_name' => 'nullable|string|max:150',
        'self_employed_address' => 'nullable|string',
        'self_employed_email_website' => 'nullable|string|max:150',
        'self_employed_telephone' => 'nullable|string|max:50',
        'self_employed_fax' => 'nullable|string|max:50',
        'self_employed_type_of_business' => 'nullable|string|max:100',
        'self_employed_years_of_operation' => 'nullable|string|max:50',
        'research_plans' => 'nullable|string',
        'career_plans' => 'nullable|string',

        // Step 6: Research, Publications, Awards
        'rnd_involvement' => 'nullable|array',
        'publications' => 'nullable|array',
        'awards' => 'nullable|array',

        // Step 7: Documents
        'birth_certificate_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'transcript_of_record_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'endorsement_1_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'endorsement_2_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'recommendation_head_agency_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'form_2a_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'form_2b_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'form_a_research_plans_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'form_b_career_plans_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'form_c_health_status_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'nbi_clearance_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'letter_of_admission_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'approved_program_of_study_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'lateral_certification_pdf' => 'nullable|file|mimes:pdf|max:20480',
        'evaluation_sheet_pdf' => 'nullable|file|mimes:pdf|max:20480', 
        'scoresheet_pdf' => 'nullable|file|mimes:pdf|max:20480',       

        // Step 8: Declaration
        'terms_and_conditions_agreed' => 'accepted',
        'applicant_signature' => 'nullable|string|max:150',
        'declaration_date' => 'nullable|date',
    ]);
    

        // Create new ApplicationForm instance
$application = new ApplicationForm();

// Set default values
$application->user_id = Auth::user()->user_id;
$application->program = $request->program;
$application->status = 'pending';
$application->submitted_at = now();

// Add these lines:
$application->applicant_status = $request->applicant_status;
$application->applicant_type = $request->applicant_type;

// Fill all non-file fields safely using validated data
$nonFileFields = collect($validated)->except([
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
])->toArray();

$application->fill($nonFileFields);

// Handle file uploads dynamically
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
    'evaluation_sheet_pdf',  
    'scoresheet_pdf',        
];

foreach ($fileFields as $field) {
    if ($request->hasFile($field)) {
        $application->$field = $request->file($field)->store('uploads/application_forms', 'public');
    }
}

// Save the application
$application->save();

ActivityLogger::log('APPLICATION_SUBMITTED', 'Application ID: ' . $application->application_form_id . ' | Program: ' . $application->program);

// Redirect with success message
return redirect()->route('dashboard')
    ->with('success', 'Application form submitted successfully.');
  }

  
public function updateDocument(Request $request, $documentType)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf|max:5120', // 5MB max
    ]);

    $user = Auth::user();
    $application = ApplicationForm::where('user_id', $user->user_id)->latest()->first();

    if (!$application) {
        return back()->with('error', 'Application not found.');
    }

    // Delete old file if exists
    $columnName = $documentType . '_pdf';
    if ($application->$columnName) {
        Storage::disk('public')->delete($application->$columnName);
    }

    // Upload new file
    $file = $request->file('document');
    $filename = $documentType . '_' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('documents/' . $user->user_id, $filename, 'public');

    // Update application
    $application->$columnName = $path;
    $application->save();

    ActivityLogger::log('DOCUMENT_UPDATED', "Document: {$documentType} | Application ID: {$application->application_form_id}");

    $documentNameMap = [
        'birth_certificate' => 'Birth Certificate',
        'transcript_of_record' => 'Transcript of Record',
        'endorsement_1' => 'Endorsement Letter 1',
        'endorsement_2' => 'Endorsement Letter 2',
        'recommendation_head_agency' => 'Recommendation of Head of Agency',
        'form_2a' => 'Form 2A - Certificate of Employment',
        'form_2b' => 'Form 2B - Optional Employment Cert.',
        'form_a_research_plans' => 'Form A - Research Plans',
        'form_b_career_plans' => 'Form B - Career Plans',
        'form_c_health_status' => 'Form C - Health Status',
        'nbi_clearance' => 'NBI Clearance',
        'letter_of_admission' => 'Letter of Admission',
        'approved_program_of_study' => 'Approved Program of Study',
        'lateral_certification' => 'Lateral Certification',
    ];

    if (isset($documentNameMap[$documentType])) {
        Remark::where('application_form_id', $application->application_form_id)
              ->where('document_name', $documentNameMap[$documentType])
              ->delete();
    }

    return back()->with('success', 'Document updated successfully! Admin remarks have been cleared.');
}

}
