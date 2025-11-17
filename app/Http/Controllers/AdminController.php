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

class AdminController extends Controller
{
    /**
     * Scholar status map (labels + optional codes if needed)
     * Keys should match values stored in scholars.status
     */
    protected $scholarStatusMap = [
        'qualifiers' => ['label' => 'Qualifiers', 'code' => '1'],
        'not_availing' => ['label' => 'Not Availing', 'code' => '2'],
        'deferred' => ['label' => 'Deferred', 'code' => '3'],
        'graduated_on_time' => ['label' => 'Graduated on Time', 'code' => '4a'],
        'graduated_ext' => ['label' => 'Graduated with Extension', 'code' => '4b'],
        'on_ext_complete_fa' => ['label' => 'On Ext - Complete FA', 'code' => '5a'],
        'on_ext_with_fa' => ['label' => 'On Ext - With FA', 'code' => '5b'],
        'on_ext_for_monitoring' => ['label' => 'On Ext - For Monitoring', 'code' => '5c'],
        'gs_on_track' => ['label' => 'GS - On Track', 'code' => '6a'],
        'leave_of_absence' => ['label' => 'Leave of Absence', 'code' => '6b'],
        'suspended' => ['label' => 'Suspended', 'code' => '6c'],
        'no_report' => ['label' => 'No Report', 'code' => '6d'],
        'non_compliance' => ['label' => 'Non-Compliance', 'code' => '7'],
        'terminated' => ['label' => 'Terminated', 'code' => '8'],
        'withdrawn' => ['label' => 'Withdrew', 'code' => '9'],
    ];

    /**
     * Admin Dashboard
     */
    public function dashboard(Request $request)
{
    $search = $request->query('search');

    $total_applicants = ApplicationForm::count();
    $pending = ApplicationForm::where('status', 'pending')->count();
    $document_verification = ApplicationForm::where('status', 'document_verification')->count();
    $approved = ApplicationForm::where('status', 'approved')->count();
    $rejected = ApplicationForm::where('status', 'rejected')->count();
    
    // CHED Scholar Statistics
    $total_ched_scholars = ChedInfo::count();
    $pending_ched_scholars = ChedInfo::where('status', 'pending')->count();
    $approved_ched_scholars = ChedInfo::where('status', 'approved')->count();
    $rejected_ched_scholars = ChedInfo::where('status', 'rejected')->count();

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
        ->values(); // Reset array keys

    $rawStatuses = Scholar::select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    $scholarStatuses = collect($this->scholarStatusMap)->mapWithKeys(function ($info, $key) use ($rawStatuses) {
        return [$info['label'] => $rawStatuses[$key] ?? 0];
    });

    return view('admin.dashboard', compact(
        'total_applicants',
        'pending',
        'document_verification',
        'approved',
        'rejected',
        'combined_recent',
        'scholarStatuses',
        'search',
        'total_ched_scholars',
        'pending_ched_scholars',
        'approved_ched_scholars',
        'rejected_ched_scholars'
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

    /**
     * View a specific application
     */
    public function showApplication($id)
    {
        $application = ApplicationForm::with('user')->findOrFail($id);
        return view('admin.applications.show', compact('application'));
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

    // Send email
    try {
        Mail::to($application->user->email)->send(new ApplicationStatusMail('approved'));
    } catch (\Throwable $e) {
        // Optional: log the email error
    }

    return redirect()->route('admin.applications')->with('success', 'Application approved.');
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

        try {
            Mail::to($application->user->email)->send(new ApplicationStatusMail('rejected', $request->remarks));
        } catch (\Throwable $e) {
            // log if needed
        }

        return redirect()->route('admin.applications')->with('success', 'Application rejected.');
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
                // ✅ NEW: Preserve verified documents
                'verified_documents' => $application->verified_documents,
            ]
        );
    } elseif ($request->status === 'rejected') {
        \App\Models\Scholar::where('application_form_id', $application->application_form_id)->delete();
    }

    // Send email
    try {
        Mail::to($application->user->email)->send(
            new ApplicationStatusMail($request->status, $request->remarks)
        );
    } catch (\Throwable $e) {
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
                if ($semester && $semester !== 'all') {
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

    if ($program === 'CHED' || $program === 'all') {
    $chedScholars = \App\Models\ChedInfo::with('user')
        ->where('status', 'approved')
        ->when($semester !== 'all', function ($query) use ($semester) {
            $query->where('school_term', $semester);
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
            
            // Create applicationForm object with CHED data
            $scholar->applicationForm = new \stdClass();
            $scholar->applicationForm->program = 'CHED';
            $scholar->applicationForm->scholarship_type = 'CHED Scholar';
            $scholar->applicationForm->school_term = $chedInfo->school_term ?? 'N/A';
            $scholar->applicationForm->school = $chedInfo->school ?? 'N/A';              // ✅ Add this
            $scholar->applicationForm->year_level = $chedInfo->year_level ?? 'N/A';      // ✅ Add thi             // ✅ Add this (bonus)
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
    $chedInfo = \App\Models\ChedInfo::with('user')->findOrFail($id);
    
    return view('admin.ched.show', compact('chedInfo'));
}

/**
 * Update CHED scholar status
 */
public function updateChedStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected'
    ]);
    
    $chedInfo = \App\Models\ChedInfo::findOrFail($id);
    $chedInfo->status = $request->status;
    $chedInfo->updated_at = now(); // Force update timestamp
    $chedInfo->save();
    
    // Debug: Log the update
    \Log::info('CHED Status Updated', [
        'id' => $id,
        'new_status' => $request->status,
        'saved' => $chedInfo->wasChanged()
    ]);
    
    return redirect()
        ->back()
        ->with('success', 'Status updated to: ' . ucfirst($request->status));
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

}
