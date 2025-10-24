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
use App\Notifications\RemarkNotification;


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
    public function dashboard()
    {
        $total_applicants = ApplicationForm::count();
        $pending = ApplicationForm::where('status', 'pending')->count();
        $document_verification = ApplicationForm::where('status', 'document_verification')->count();
        $for_interview = ApplicationForm::where('status', 'for_interview')->count();
        $approved = ApplicationForm::where('status', 'approved')->count();
        $rejected = ApplicationForm::where('status', 'rejected')->count();

        $recent_applicants = ApplicationForm::latest()->take(10)->get();

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
            'for_interview',
            'approved',
            'rejected',
            'recent_applicants',
            'scholarStatuses'
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
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($query, $search) {
                $search = Str::lower($search);
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
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
        $application = \App\Models\ApplicationForm::with('documentRemarks')->findOrFail($id);
        return view('admin.applications.show', compact('application'));
    }


    /**
    * Store a new remark for an application
    */
    public function storeRemark(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'document_remarks' => 'required|string|max:2000',
        ]);

        // Create the new remark linked to the application (evaluation)
        $remark = \App\Models\Remark::create([
            'evaluation_id' => $id, // corresponds to the application/evaluation ID
            'content' => $request->document_remarks,
        ]);

        // Optionally, notify the applicant
        // (Assuming there's a user relation in your Evaluation/Application model)
        $application = \App\Models\ApplicationForm::where('application_form_id', $id)->first();

        // ✅ Notify the applicant using Laravel’s built-in notification system
        if ($application && $application->user) {
            $application->user->notify(new \App\Notifications\RemarkNotification($remark));
        }        

        return back()->with('success', 'Remark sent successfully.');
    }

    /**
     * Approve application
     */
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

    /**
     * Update status with optional remarks
     */
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

    // ✅ If approved, automatically create or update a scholar record
    if ($request->status === 'approved') {
        \App\Models\Scholar::updateOrCreate(
            [
                'user_id' => $application->user_id,
                'application_form_id' => $application->application_form_id, // ✅ use the correct FK column
            ],
            [
                'status' => 'qualifiers', // ✅ consistent with your scholar status labels
                'start_date' => now(),
                'end_date' => null,
            ]
        );
    }

    // ✅ Remove scholar record if rejected
    if ($request->status === 'rejected') {
        \App\Models\Scholar::where('application_form_id', $application->application_form_id)->delete();
    }

    try {
        Mail::to($application->user->email)->send(
            new ApplicationStatusMail($request->status, $request->remarks)
        );
    } catch (\Throwable $e) {
        // Optional: Log error if email fails
    }

    return redirect()->route('admin.applications.show', $id)
        ->with('success', 'Application status updated successfully!');
}


    /**
     * Reports Summary
     */
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
    public function viewScholars()
    {
        $scholars = Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', function ($query) {
                $query->where('status', 'approved');
            })
            ->latest()
            ->paginate(10);

        return view('admin.scholars.index', compact('scholars'));
    }
}
