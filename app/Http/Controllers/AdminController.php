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
            'recent_applicants',
            'scholarStatuses',
            'search'
        ));
    }


    /**
     * View / list applications
     */
    public function viewApplications(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $applications = ApplicationForm::with('user')
            ->whereIn('status', ['pending', 'document_verification'])
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

        // ✅ FIX: Correct key reference
        Scholar::firstOrCreate(
            ['application_form_id' => $application->application_form_id], // Use the actual ApplicationForm ID
            [
                'user_id' => $application->user_id,
                'status' => 'qualifiers',
                'start_date' => now(),
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
                ]
            );
        } elseif ($request->status === 'rejected') {
            \App\Models\Scholar::where('application_form_id', $application->application_form_id)->delete();
        }

        // ✅ Send email (optional for auto status updates)
        try {
            Mail::to($application->user->email)->send(
                new ApplicationStatusMail($request->status, $request->remarks)
            );
        } catch (\Throwable $e) {
        }

        // ✅ If request came from fetch() → return JSON instead of redirect
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
        $program = $request->query('program');
        $semester = $request->query('semester');
        $search = $request->query('search'); // from your search bar

        $scholars = \App\Models\Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', function ($query) use ($program, $semester) {
                if ($program && $program !== 'all') {
                    $query->where('program', $program);
                }
                if ($semester && $semester !== 'all') {
                    $query->where('school_term', $semester);
                }
            })
            // 🔍 First-letter search only
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $firstLetter = substr($search, 0, 1); // take only first character
                    $userQuery->where('first_name', 'like', "{$firstLetter}%")
                        ->orWhere('last_name', 'like', "{$firstLetter}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.scholars.index', compact('scholars', 'program', 'semester', 'search'));
    }
}
