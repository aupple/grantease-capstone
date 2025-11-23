<?php

namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ChedInfo;
use App\Models\Remark;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    /**
     * Scholar status map (labels + optional codes if needed)
     * Keys should match values stored in scholars.status
     */
    protected $scholarStatusMap = [
    'qualifiers' => ['label' => 'Qualifiers', 'code' => '1', 'color' => '#4CAF50'],
    'not_availing' => ['label' => 'Not Availing', 'code' => '2', 'color' => '#FF9800'],
    'deferred' => ['label' => 'Deferred', 'code' => '3', 'color' => '#03A9F4'],
    'graduated_on_time' => ['label' => 'Graduated on Time', 'code' => '4a', 'color' => '#9C27B0'],
    'graduated_ext' => ['label' => 'Graduated with Extension', 'code' => '4b', 'color' => '#F44336'],
    'on_ext_complete_fa' => ['label' => 'On Ext - Complete FA', 'code' => '5a', 'color' => '#FFC107'],
    'on_ext_with_fa' => ['label' => 'On Ext - With FA', 'code' => '5b', 'color' => '#00BCD4'],
    'on_ext_for_monitoring' => ['label' => 'On Ext - For Monitoring', 'code' => '5c', 'color' => '#607D8B'],
    'gs_on_track' => ['label' => 'GS - On Track', 'code' => '6a', 'color' => '#795548'],
    'leave_of_absence' => ['label' => 'Leave of Absence', 'code' => '6b', 'color' => '#E91E63'],
    'suspended' => ['label' => 'Suspended', 'code' => '6c', 'color' => '#8BC34A'],
    'no_report' => ['label' => 'No Report', 'code' => '6d', 'color' => '#2196F3'],
    'non_compliance' => ['label' => 'Non-Compliance', 'code' => '7', 'color' => '#CDDC39'],
    'terminated' => ['label' => 'Terminated', 'code' => '8', 'color' => '#009688'],
    'withdrawn' => ['label' => 'Withdrew', 'code' => '9', 'color' => '#673AB7'],
];
    /**
     * Admin Dashboard
     */
   public function dashboard(Request $request)
{
    $search = $request->query('search');

    // DOST Application Statistics
    $total_applicants = ApplicationForm::count();
    $pending_dost = ApplicationForm::where('status', 'pending')->count();
    $document_verification = ApplicationForm::where('status', 'document_verification')->count();
    $approved_dost = ApplicationForm::where('status', 'approved')->count();
    $rejected_dost = ApplicationForm::where('status', 'rejected')->count();
    
    // CHED Scholar Statistics
    $total_ched_scholars = ChedInfo::count();
    $pending_ched_scholars = ChedInfo::where('status', 'pending')->count();
    $approved_ched_scholars = ChedInfo::where('status', 'approved')->count();
    $rejected_ched_scholars = ChedInfo::where('status', 'rejected')->count();

    // ✅ COMBINED Statistics for Summary Cards (using correct variable names)
    $total_scholars = $approved_ched_scholars + $approved_dost;
    $pending = $pending_dost + $pending_ched_scholars;
    $approved = $approved_dost + $approved_ched_scholars;
    $rejected = $rejected_dost + $rejected_ched_scholars;

    // Get DOST applications
    $recent_applicants = ApplicationForm::with('user')
        ->latest()
        ->when($search, function ($query, $search) {
            $firstLetter = substr($search, 0, 1);
            $query->whereHas('user', function ($q) use ($firstLetter) {
                $q->where('first_name', 'like', "{$firstLetter}%")
                    ->orWhere('last_name', 'like', "{$firstLetter}%");
            });
        })
        ->take(10)
        ->get();

    // Get CHED scholars
    $recent_ched_scholars = ChedInfo::with('user')
        ->latest()
        ->when($search, function ($query, $search) {
            $firstLetter = substr($search, 0, 1);
            $query->whereHas('user', function ($q) use ($firstLetter) {
                $q->where('first_name', 'like', "{$firstLetter}%")
                    ->orWhere('last_name', 'like', "{$firstLetter}%");
            });
        })
        ->take(10)
        ->get();

    // ✅ Combine and sort by date (most recent first)
    $combined_recent = collect($recent_applicants)
        ->merge($recent_ched_scholars)
        ->sortByDesc('created_at')
        ->take(10)
        ->values();

    // ✅ Get scholar statuses from database (snake_case format)
    $rawStatuses = Scholar::select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    // ✅ Map database statuses to display labels with counts
    $scholarStatuses = collect($this->scholarStatusMap)->mapWithKeys(function ($info, $key) use ($rawStatuses) {
        return [$info['label'] => $rawStatuses[$key] ?? 0];
    });

    // ✅ Extract colors in the same order as labels for the pie chart
    $scholarColors = collect($this->scholarStatusMap)->pluck('color')->values();

    return view('admin.dashboard', compact(
        'total_applicants',
        'pending',
        'document_verification',
        'approved',
        'rejected',
        'combined_recent',
        'scholarStatuses',
        'scholarColors',
        'search',
        'total_ched_scholars',
        'pending_ched_scholars',
        'approved_ched_scholars',
        'rejected_ched_scholars',
        'total_scholars'
    ));
}

    /**
     * View / list applications
     */
    public function viewApplications(Request $request)
{
    $status = $request->query('status');
    $search = $request->query('search');

    // Only DOST applicants who are NOT yet scholars
    $applications = ApplicationForm::with('user')
        ->whereIn('status', ['pending', 'document_verification'])
        ->whereDoesntHave('scholar') // ✅ Exclude those who are already scholars
        ->when($search, function ($query, $search) {
            $firstLetter = Str::lower(substr($search, 0, 1));

            $query->whereHas('user', function ($userQuery) use ($firstLetter) {
                $userQuery->whereRaw('LOWER(first_name) LIKE ?', ["{$firstLetter}%"])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', ["{$firstLetter}%"]);
            });
        })
        ->latest()
        ->paginate(10);

    return view('admin.applications.index', compact('applications', 'status', 'search'));
}

    public function showApplication($id)
{
    $application = ApplicationForm::with(['user', 'remarks.attachments'])->findOrFail($id);
    
    // ✅ FIXED: Handle empty remarks collection properly
    $allRemarks = $application->remarks ? $application->remarks->keyBy('document_name') : collect([]);
    
    return view('admin.applications.show', compact('application', 'allRemarks'));
}

public function printApplicationForm($id)
{
    $application = ApplicationForm::with('user')->findOrFail($id);
    
    try {
        $pdf = new \setasign\Fpdi\Fpdi();
        $templatePath = storage_path('app/pdf-templates/Application-Form_STRAND.pdf');
        
        // Check if template exists
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'PDF template not found.');
        }
        
        // Get page count
        $pageCount = $pdf->setSourceFile($templatePath);
        
        // Import first page
        $templateId = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($templateId);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($templateId);
        
        // Set font for filling
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        
        // ===== FILL FORM FIELDS WITH CORRECTED COORDINATES =====
        
        // Application Number (top left) - KEEP AS IS (WORKING)
        $pdf->SetXY(41, 14);
        $pdf->Write(0, $application->application_form_id ?? '');
        
        // School Term checkboxes - KEEP AS IS (WORKING)
        if (is_array($application->school_term)) {
            $term = $application->school_term[0] ?? '';
        } else {
            $term = $application->school_term ?? '';
        }
        
        if (stripos($term, 'First') !== false) {
            $pdf->SetXY(32.5, 74);
            $pdf->Write(0, 'X');
        } elseif (stripos($term, 'Second') !== false) {
            $pdf->SetXY(48, 67);
            $pdf->Write(0, 'X');
        } elseif (stripos($term, 'Third') !== false) {
            $pdf->SetXY(66, 67);
            $pdf->Write(0, 'X');
        }
        
        // === PERSONAL INFORMATION ===
        
        // Row a: Last Name, First Name, Middle Name
        // Last Name - KEEP AS IS (WORKING)
        $pdf->SetXY(25, 93);
        $pdf->Write(0, $application->user->last_name ?? '');
        
        // First Name
        $pdf->SetXY(87, 93);
        $pdf->Write(0, $application->user->first_name ?? '');
        
        // Middle Name
        $pdf->SetXY(155, 93);
        $pdf->Write(0, $application->user->middle_name ?? '');
        
        // Row b: Permanent Address No., Street, Barangay, City/Municipality, Province
        // No.
        $pdf->SetXY(25, 94);
        $pdf->Write(0, $application->address_no ?? '');
        
        // Street
        $pdf->SetXY(55, 94);
        $pdf->Write(0, $application->address_street ?? '');
        
        // Barangay
        $pdf->SetXY(95, 94);
        $barangayName = $this->getLocationName($application->barangay, 'barangay');
        $pdf->Write(0, $barangayName);
        
        // City/Municipality
        $pdf->SetXY(135, 94);
        $cityName = $this->getLocationName($application->city, 'city');
        $pdf->Write(0, $cityName);
        
        // Province
        $pdf->SetXY(180, 94);
        $provinceName = $this->getLocationName($application->province, 'province');
        $pdf->Write(0, $provinceName);
        
        // Row c: Zip Code, Region, District, Passport No., Email Address
        // Zip Code
        $pdf->SetXY(28, 102);
        $pdf->Write(0, $application->zip_code ?? '');
        
        // Region (converted to Roman numeral)
        $pdf->SetXY(64, 102);
        $regionName = $this->convertRegionToRoman($application->region);
        $pdf->Write(0, $regionName);
        
        // District
        $pdf->SetXY(92, 102);
        $pdf->Write(0, $application->district ?? '');
        
        // Passport No.
        $pdf->SetXY(131, 102);
        $pdf->Write(0, $application->passport_no ?? '');
        
        // Email Address
        $pdf->SetXY(165, 102);
        $pdf->Write(0, $application->email_address ?? $application->user->email ?? '');
        
        // Row d: Current Mailing Address
        $pdf->SetXY(30, 110);
        $pdf->Write(0, $application->current_mailing_address ?? '');
        
        // Row e: Telephone Nos
        $pdf->SetXY(27, 118);
        $pdf->Write(0, $application->telephone_nos ?? '');
        
        // Row f: Civil Status, Date of Birth, Age, Sex
        // Civil Status
        $pdf->SetXY(33, 127);
        $pdf->Write(0, $application->civil_status ?? '');
        
        // Date of Birth
        $pdf->SetXY(72, 127);
        $pdf->Write(0, $application->date_of_birth ?? '');
        
        // Age
        $pdf->SetXY(121, 127);
        $pdf->Write(0, $application->age ?? '');
        
        // Sex
        $pdf->SetXY(169, 127);
        $pdf->Write(0, $application->sex ?? '');
        
        // Row g: Father's Name, Mother's Name
        // Father's Name
        $pdf->SetXY(28, 135);
        $pdf->Write(0, $application->father_name ?? '');
        
        // Mother's Name
        $pdf->SetXY(119, 135);
        $pdf->Write(0, $application->mother_name ?? '');
        
        // If there's a second page, import it
        if ($pageCount > 1) {
            for ($pageNo = 2; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }
        
        // Output PDF
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Application_Form_' . $application->application_form_id . '.pdf"');
            
    } catch (\Exception $e) {
        \Log::error('PDF Generation Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
    }
}

/**
 * Helper function to get location names from PSGC codes
 */
private function getLocationName($code, $type = 'city')
{
    if (!$code) return 'N/A';
    
    $jsonUrls = [
        'province' => 'https://psgc.gitlab.io/api/provinces/',
        'city' => 'https://psgc.gitlab.io/api/cities-municipalities/',
        'barangay' => 'https://psgc.gitlab.io/api/barangays/',
        'district' => 'https://psgc.gitlab.io/api/districts/',
    ];
    
    if (!isset($jsonUrls[$type])) {
        return 'Unknown';
    }
    
    $cacheFile = storage_path("app/psgc_$type.json");
    $data = null;
    
    // Use cache if available
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
    }
    
    // If cache is missing/invalid, try fetching from API
    if (empty($data)) {
        try {
            $json = @file_get_contents($jsonUrls[$type]);
            if ($json !== false) {
                $data = json_decode($json, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    file_put_contents($cacheFile, json_encode($data));
                }
            }
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    // Find code in data
    if (is_array($data)) {
        foreach ($data as $item) {
            if (isset($item['code']) && $item['code'] == $code) {
                return $item['name'];
            }
        }
    }
    
    return 'Unknown';
}

/**
 * Convert region name to Roman numeral format
 */
private function convertRegionToRoman($region)
{
    if (!$region) return 'N/A';
    
    // Mapping of region names to Roman numerals
    $regionMap = [
        'Region I' => 'Region I',
        'Region II' => 'Region II',
        'Region III' => 'Region III',
        'Region IV-A' => 'Region IV-A',
        'Region IV-B' => 'Region IV-B',
        'Region V' => 'Region V',
        'Region VI' => 'Region VI',
        'Region VII' => 'Region VII',
        'Region VIII' => 'Region VIII',
        'Region IX' => 'Region IX',
        'Region X' => 'Region X',
        'Region XI' => 'Region XI',
        'Region XII' => 'Region XII',
        'Region XIII' => 'Region XIII',
        'NCR' => 'NCR',
        'CAR' => 'CAR',
        'BARMM' => 'BARMM',
        // Alternative names
        'Northern Mindanao' => 'Region X',
        'Davao Region' => 'Region XI',
        'SOCCSKSARGEN' => 'Region XII',
        'Caraga' => 'Region XIII',
        'Ilocos Region' => 'Region I',
        'Cagayan Valley' => 'Region II',
        'Central Luzon' => 'Region III',
        'CALABARZON' => 'Region IV-A',
        'MIMAROPA' => 'Region IV-B',
        'Bicol Region' => 'Region V',
        'Western Visayas' => 'Region VI',
        'Central Visayas' => 'Region VII',
        'Eastern Visayas' => 'Region VIII',
        'Zamboanga Peninsula' => 'Region IX',
        'National Capital Region' => 'NCR',
        'Cordillera Administrative Region' => 'CAR',
        'Bangsamoro Autonomous Region in Muslim Mindanao' => 'BARMM',
    ];
    
    // Check if the region matches any mapping
    foreach ($regionMap as $key => $value) {
        if (stripos($region, $key) !== false) {
            return $value;
        }
    }
    
    // If already in Roman numeral format, return as is
    if (preg_match('/^(I{1,3}|IV|V|VI{0,3}|IX|X{1,3}|XI{1,2}|NCR|CAR|BARMM)(-[A-Z])?$/', $region)) {
        return $region;
    }
    
    return $region; // Return original if no match found
}

    public function showScholar($id)
    {
        $scholar = Scholar::with(['user', 'applicationForm'])->findOrFail($id);
        return view('admin.scholars.show', compact('scholar'));
    }

    public function verifyDocument(Request $request, $id)
    {
        $application = ApplicationForm::findOrFail($id);
        $document = $request->input('document');
        $verified = $request->input('verified');

        // Decode existing verified documents (or default to empty array)
        $verifiedDocs = $application->verified_documents ? json_decode($application->verified_documents, true) : [];

        // ✅ FIX: Use the exact document name as the key (don't convert to snake_case)
        if ($verified) {
            $verifiedDocs[$document] = true;
        } else {
            unset($verifiedDocs[$document]);
        }

        $application->verified_documents = json_encode($verifiedDocs);
        $application->save();

        // ✅ Define ALL document names that match your Blade file
        $requiredDocuments = [
            'Passport Picture',
            'Birth Certificate',
            'Transcript of Record',
            'Endorsement Letter 1',
            'Endorsement Letter 2',
            'Recommendation of Head of Agency',
            'Form 2A - Certificate of Employment',
            'Form 2B - Optional Employment Cert.',
            'Form A - Research Plans',
            'Form B - Career Plans',
            'Form C - Health Status',
            'NBI Clearance',
            'Letter of Admission',
            'Approved Program of Study',
            'Lateral Certification',
        ];

        // ✅ Filter only uploaded documents from required list
        $uploadedRequiredDocs = array_filter($requiredDocuments, function ($doc) use ($application) {
            $documents = [
                'Passport Picture' => $application->passport_picture ?? null,
                'Birth Certificate' => $application->birth_certificate_pdf ?? null,
                'Transcript of Record' => $application->transcript_of_record_pdf ?? null,
                'Endorsement Letter 1' => $application->endorsement_1_pdf ?? null,
                'Endorsement Letter 2' => $application->endorsement_2_pdf ?? null,
                'Recommendation of Head of Agency' => $application->recommendation_head_agency_pdf ?? null,
                'Form 2A - Certificate of Employment' => $application->form_2a_pdf ?? null,
                'Form 2B - Optional Employment Cert.' => $application->form_2b_pdf ?? null,
                'Form A - Research Plans' => $application->form_a_research_plans_pdf ?? null,
                'Form B - Career Plans' => $application->form_b_career_plans_pdf ?? null,
                'Form C - Health Status' => $application->form_c_health_status_pdf ?? null,
                'NBI Clearance' => $application->nbi_clearance_pdf ?? null,
                'Letter of Admission' => $application->letter_of_admission_pdf ?? null,
                'Approved Program of Study' => $application->approved_program_of_study_pdf ?? null,
                'Lateral Certification' => $application->lateral_certification_pdf ?? null,
            ];
            return !empty($documents[$doc]);
        });

        // ✅ Count how many uploaded required docs are verified
        $verifiedCount = count(array_filter($uploadedRequiredDocs, function ($doc) use ($verifiedDocs) {
            return isset($verifiedDocs[$doc]) && $verifiedDocs[$doc] === true;
        }));

        $totalRequired = count($uploadedRequiredDocs);
        $allVerified = $verifiedCount === $totalRequired && $totalRequired > 0;

        // ✅ Update status when all required docs are verified
        if ($allVerified && $application->status === 'pending') {
            $application->status = 'document_verification';
            $application->save();
        } elseif (!$allVerified && $application->status === 'document_verification') {
            // ✅ If unchecking documents, revert to pending
            $application->status = 'pending';
            $application->save();
        }

        // ✅ DEBUG: check if update actually happens
        \Log::info('Document verification update', [
            'id' => $application->id,
            'document' => $document,
            'verified' => $verified,
            'verified_count' => $verifiedCount,
            'required_count' => $totalRequired,
            'all_verified' => $allVerified,
            'status' => $application->status,
            'verified_docs' => $verifiedDocs,
        ]);

        return response()->json([
            'success' => true,
            'verified_documents' => $verifiedDocs,
            'verified_count' => $verifiedCount,
            'total_required' => $totalRequired,
            'all_verified' => $allVerified,
            'status' => $application->status,
        ]);
    }

   public function approveApplication($id)
{
    $application = ApplicationForm::with('user')->findOrFail($id);

    // Update application status
    $application->status = 'approved';
    $application->remarks = null;
    $application->save();

    // ✅ FIX: Copy verified_documents when creating scholar
    Scholar::firstOrCreate(
        ['application_form_id' => $application->application_form_id],
        [
            'user_id' => $application->user_id,
            'status' => 'qualifiers',
            'start_date' => now(),
            // ✅ NEW: Preserve verified documents
            'verified_documents' => $application->verified_documents,
        ]
    );

    // Send email only to DOST applicants
    if ($application->user->program_type === 'DOST') {
        try {
            $applicantName = $application->user->first_name . ' ' . $application->user->last_name;
            $remarks = 'Congratulations! Your DOST scholarship application has been approved.';
            
            Mail::to($application->user->email)->send(
                new ApplicationStatusMail('approved', $applicantName, 'DOST', $remarks)
            );
            
            \Log::info('DOST Approval email sent to: ' . $application->user->email);
        } catch (\Throwable $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
        }
    }

    return redirect()->route('admin.applications')->with('success', 'Application approved and email notification sent.');
}

    /**
     * Reject application
     */
   public function rejectApplication(Request $request, $id)
{
    $request->validate([
        'remarks' => 'required|string'
    ]);

    $application = ApplicationForm::with('user')->findOrFail($id);
    $application->status = 'rejected';
    $application->remarks = $request->remarks;
    $application->save();

    // Send email only to DOST applicants
    if ($application->user->program_type === 'DOST') {
        try {
            $applicantName = $application->user->first_name . ' ' . $application->user->last_name;
            
            Mail::to($application->user->email)->send(
                new ApplicationStatusMail('rejected', $applicantName, 'DOST', $request->remarks)
            );
            
            \Log::info('DOST Rejection email sent to: ' . $application->user->email);
        } catch (\Throwable $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
        }
    }

    return redirect()->route('admin.applications')->with('success', 'Application rejected and email notification sent.');
}

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,document_verification,for_interview,approved,rejected',
        'remarks' => 'nullable|string',
    ]);

    $application = ApplicationForm::with('user')->findOrFail($id);
    $application->status = $request->status;
    $application->remarks = $request->remarks;
    $application->save();

    // ✅ Handle scholar creation/removal
    if ($request->status === 'approved') {
        \App\Models\Scholar::updateOrCreate(
            [
                'user_id' => $application->user_id,
                'application_form_id' => $application->application_form_id,
            ],
            [
                'status' => 'qualifiers',
                'start_date' => now(),
                'end_date' => null,
                'verified_documents' => $application->verified_documents,
            ]
        );
    } elseif ($request->status === 'rejected') {
        \App\Models\Scholar::where('application_form_id', $application->application_form_id)->delete();
    }

    // Send email only to DOST applicants
    if ($application->user->program_type === 'DOST') {
        try {
            $applicantName = $application->user->first_name . ' ' . $application->user->last_name;
            
            Mail::to($application->user->email)->send(
                new ApplicationStatusMail($request->status, $applicantName, 'DOST', $request->remarks)
            );
        } catch (\Throwable $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
        }
    }

    // If request came from fetch() → return JSON
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'status' => $application->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    // Otherwise, normal redirect
    return redirect()->route('admin.applications.show', $id)
        ->with('success', 'Application status updated successfully!');
}

    public function reportSummary(Request $request)
    {
        $total_applicants = ApplicationForm::count();
        $pending = ApplicationForm::where('status', 'pending')->count();
        $document_verification = ApplicationForm::where('status', 'document_verification')->count();
        $for_interview = ApplicationForm::where('status', 'for_interview')->count();
        $approved = ApplicationForm::where('status', 'approved')->count();
        $rejected = ApplicationForm::where('status', 'rejected')->count();

        $statusFilter = $request->query('status');

        $query = Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', function ($q) {
                $q->where('status', 'approved');
            });

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $scholars = $query->latest()->paginate(10);

        $rawStatuses = Scholar::select('status', DB::raw('count(*) as total'))
            ->whereHas('applicationForm', fn($q) => $q->where('status', 'approved'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $scholarStatuses = collect($this->scholarStatusMap)->mapWithKeys(function ($info, $key) use ($rawStatuses) {
            return [$info['label'] => $rawStatuses[$key] ?? 0];
        });

        return view('admin.reports.index', compact(
            'total_applicants',
            'pending',
            'document_verification',
            'for_interview',
            'approved',
            'rejected',
            'scholars',
            'statusFilter',
            'scholarStatuses'
        ));
    }

    /**
     * PDF Report Download
     */
    public function downloadReportPdf()
    {
        $data = [
            'total_applicants' => ApplicationForm::count(),
            'pending' => ApplicationForm::where('status', 'pending')->count(),
            'document_verification' => ApplicationForm::where('status', 'document_verification')->count(),
            'for_interview' => ApplicationForm::where('status', 'for_interview')->count(),
            'approved' => ApplicationForm::where('status', 'approved')->count(),
            'rejected' => ApplicationForm::where('status', 'rejected')->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        return $pdf->download('application-report.pdf');
    }

    /**
     * Rejected Applications
     */
    public function rejectedApplications()
    {
        $rejectedApplications = ApplicationForm::where('status', 'rejected')
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.rejected.index', compact('rejectedApplications'));
    }

    /**
     * Show details of a single rejected application
     */
    public function showRejected($id)
    {
        $application = ApplicationForm::where('status', 'rejected')
            ->with('user')
            ->findOrFail($id);

        return view('admin.rejected.show', compact('application'));
    }

    /**
     * View approved scholars
     */
    public function viewScholars(Request $request)
{
    $program = $request->query('program', 'all');
    $semester = $request->query('semester', 'all');
    $search = $request->query('search');

    $combinedScholars = collect();

    // Get DOST scholars
    if ($program === 'DOST' || $program === 'all') {
        $dostScholars = \App\Models\Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', function ($q) use ($semester) {
                $q->where('program', 'DOST');
                // DOST uses "First" and "Second"
                if ($semester !== 'all') {
                    $q->where('school_term', $semester);
                }
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $firstLetter = substr($search, 0, 1);
                    $userQuery->where('first_name', 'like', "{$firstLetter}%")
                        ->orWhere('last_name', 'like', "{$firstLetter}%");
                });
            })
            ->latest()
            ->get()
            ->each(function ($scholar) {
                $scholar->program_type = 'DOST';
            });
        
        $combinedScholars = $combinedScholars->merge($dostScholars);
    }

    // Get CHED scholars
    if ($program === 'CHED' || $program === 'all') {
        // Convert semester format for CHED (CHED uses "1st Semester", "2nd Semester")
        $chedSemester = $semester;
        if ($semester === 'First Semester') {
            $chedSemester = '1st Semester';
        } elseif ($semester === 'Second Semester') {
            $chedSemester = '2nd Semester';
        }
        
        $chedScholars = \App\Models\ChedInfo::with('user')
            ->where('status', 'approved')
            // Filter by converted semester value
            ->when($semester !== 'all', function ($query) use ($chedSemester) {
                $query->where('school_term', $chedSemester);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $firstLetter = substr($search, 0, 1);
                    $userQuery->where('first_name', 'like', "{$firstLetter}%")
                        ->orWhere('last_name', 'like', "{$firstLetter}%");
                });
            })
            ->latest()
            ->get()
            ->map(function ($chedInfo) {
                // Create a scholar-like object
                $scholar = new \stdClass();
                $scholar->id = $chedInfo->id;
                $scholar->user = $chedInfo->user;
                $scholar->status = $chedInfo->status;
                $scholar->updated_at = $chedInfo->updated_at;
                $scholar->program_type = 'CHED';
                
                // Convert CHED semester format to match DOST format
                $displaySemester = $chedInfo->school_term ?? 'N/A';
                if ($displaySemester === '1st Semester') {
                    $displaySemester = 'First Semester';
                } elseif ($displaySemester === '2nd Semester') {
                    $displaySemester = 'Second Semester';
                }
                
                // Create applicationForm object with CHED data
                $scholar->applicationForm = new \stdClass();
                $scholar->applicationForm->program = 'CHED';
                $scholar->applicationForm->scholarship_type = 'CHED Scholar';
                $scholar->applicationForm->school_term = $displaySemester;
                $scholar->applicationForm->school = $chedInfo->school ?? 'N/A';
                $scholar->applicationForm->year_level = $chedInfo->year_level ?? 'N/A';
                $scholar->applicationForm->bs_university = 'N/A';  // For DOST compatibility
                
                return $scholar;
            });
        
        $combinedScholars = $combinedScholars->merge($chedScholars);
    }

    // Sort by updated_at
    $combinedScholars = $combinedScholars->sortByDesc(function($scholar) {
        return $scholar->updated_at;
    })->values();

    // Manual pagination
    $perPage = 10;
    $currentPage = $request->query('page', 1);
    $total = $combinedScholars->count();
    $offset = ($currentPage - 1) * $perPage;
    
    $paginatedItems = $combinedScholars->slice($offset, $perPage)->values();

    $scholars = new \Illuminate\Pagination\LengthAwarePaginator(
        $paginatedItems,
        $total,
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('admin.scholars.index', [
        'scholars' => $scholars,
        'program' => $program,
        'semester' => $semester,
        'search' => $search
    ]);
}
    /**
 * View all CHED scholars
 */
public function viewChedScholars(Request $request)
{
    $search = $request->query('search');
    $status = $request->query('status');

    $chedScholars = \App\Models\ChedInfo::with('user')
        ->when($status, function ($query, $status) {
            $query->where('status', $status);
        })
        ->when($search, function ($query, $search) {
            $query->whereHas('user', function ($userQuery) use ($search) {
                $firstLetter = substr($search, 0, 1);
                $userQuery->where('first_name', 'like', "{$firstLetter}%")
                    ->orWhere('last_name', 'like', "{$firstLetter}%");
            });
        })
        ->latest()
        ->paginate(15);

    return view('admin.ched.index', compact('chedScholars', 'search', 'status'));
}

/**
 * View individual CHED scholar details
 */
public function showChedScholar($id)
{
    $chedInfo = ChedInfo::findOrFail($id);

    // Helper function to fetch PSGC names with caching
    $fetchPSGCName = function ($type, $code) {
        if (!$code) return 'N/A';

        return Cache::remember("psgc_{$type}_{$code}", 3600, function () use ($type, $code) {
            try {
                $url = match($type) {
                    'province' => "https://psgc.gitlab.io/api/provinces/{$code}/",
                    'city'     => "https://psgc.gitlab.io/api/cities-municipalities/{$code}/",
                    'barangay' => "https://psgc.gitlab.io/api/barangays/{$code}/",
                };

                $response = Http::timeout(10)->get($url);

                if ($response->successful()) {
                    return $response->json()['name'] ?? 'N/A';
                }
            } catch (\Exception $e) {
                \Log::error("PSGC fetch failed for {$type} code {$code}: ".$e->getMessage());
            }

            return 'N/A';
        });
    };

    // Fetch readable names
    $provinceName = $fetchPSGCName('province', $chedInfo->province);
    $cityName     = $fetchPSGCName('city', $chedInfo->city);
    $barangayName = $fetchPSGCName('barangay', $chedInfo->barangay);

    return view('admin.ched.show', compact(
        'chedInfo',
        'provinceName',
        'cityName',
        'barangayName'
    ));
}

/**
 * Update CHED scholar status
 */
public function updateChedStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected',
        'remarks' => 'nullable|string'
    ]);
    
    $chedInfo = \App\Models\ChedInfo::with('user')->findOrFail($id);
    $oldStatus = $chedInfo->status; // Track old status
    
    $chedInfo->status = $request->status;
    $chedInfo->updated_at = now();
    $chedInfo->save();
    
    // Send email to CHED applicants (only if status changed and not pending)
    if ($chedInfo->user && 
        $chedInfo->user->program_type === 'CHED' && 
        $oldStatus !== $request->status &&
        in_array($request->status, ['approved', 'rejected'])) {
        
        try {
            $applicantName = $chedInfo->user->first_name . ' ' . $chedInfo->user->last_name;
            
            // Set default messages if no remarks provided
            if ($request->status === 'approved') {
                $remarks = $request->remarks ?? 'Congratulations! Your CHED scholarship application has been approved.';
            } elseif ($request->status === 'rejected') {
                $remarks = $request->remarks ?? 'Unfortunately, your CHED scholarship application has been rejected. Please review the remarks for more details.';
            } else {
                $remarks = $request->remarks;
            }
            
            Mail::to($chedInfo->user->email)->send(
                new ApplicationStatusMail($request->status, $applicantName, 'CHED', $remarks)
            );
            
            \Log::info('CHED Status email sent', [
                'email' => $chedInfo->user->email,
                'status' => $request->status,
                'applicant' => $applicantName
            ]);
            
        } catch (\Throwable $e) {
            \Log::error('CHED Email send failed: ' . $e->getMessage());
        }
    }
    
    // Debug: Log the update
    \Log::info('CHED Status Updated', [
        'id' => $id,
        'old_status' => $oldStatus,
        'new_status' => $request->status,
        'saved' => $chedInfo->wasChanged()
    ]);
    
    return redirect()
        ->back()
        ->with('success', 'Status updated to: ' . ucfirst($request->status) . '. Email notification sent to applicant.');
}

public function saveDocumentRemark(Request $request, $applicationId)
{
    $documentLabel = $request->input('document');
    $remarkText = $request->input('remark');
    
    // Find existing remark or create new one
    $remark = Remark::updateOrCreate(
        [
            'application_form_id' => $applicationId,
            'document_name' => $documentLabel,
        ],
        [
            'remark_text' => $remarkText,
        ]
    );
    
    return response()->json([
        'success' => true,
        'message' => 'Remark saved successfully',
        'document' => $documentLabel,
        'remark' => $remarkText,
    ]);
}

public function updateScholarStatus(Request $request, $id)
{
    try {
        $validStatuses = array_keys($this->scholarStatusMap);
        
        $request->validate([
            'status' => [
                'required',
                'string',
                'in:' . implode(',', $validStatuses)
            ]
        ]);

        $scholar = Scholar::findOrFail($id);
        $oldStatus = $scholar->status;
        $oldLabel = $this->scholarStatusMap[$oldStatus]['label'] ?? $oldStatus;
        $newLabel = $this->scholarStatusMap[$request->status]['label'];
        
        $scholar->status = $request->status;
        $scholar->save();

        \Log::info("Scholar #{$id} status updated", [
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'updated_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', "Scholar status successfully updated from '{$oldLabel}' to '{$newLabel}'!");
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->with('error', 'Invalid status selected. Please choose a valid status from the list.');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('admin.scholars')->with('error', 'Scholar not found.');
    } catch (\Exception $e) {
        \Log::error('Failed to update scholar status: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to update scholar status. Please try again.');
    }
}

}
