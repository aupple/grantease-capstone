<?php

namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ✅ Admin Dashboard
    public function dashboard()
    {
        $total_applicants = ApplicationForm::count();
        $pending = ApplicationForm::where('status', 'pending')->count();
        $approved = ApplicationForm::where('status', 'approved')->count();
        $rejected = ApplicationForm::where('status', 'rejected')->count();

        $scholarStatuses = Scholar::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'total_applicants',
            'pending',
            'approved',
            'rejected',
            'scholarStatuses'
        ));
    }

    // ✅ View all submitted applications
    public function viewApplications(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $applications = ApplicationForm::with('user')
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "$search%")
                          ->orWhere('middle_name', 'like', "$search%")
                          ->orWhere('last_name', 'like', "$search%");
                    })
                    ->orWhere('program', 'like', "$search%")
                    ->orWhere('status', 'like', "$search%");
                });
            })
            ->latest()
            ->paginate(5);

        return view('admin.applications.index', compact('applications', 'status', 'search'));
    }

    // ✅ View a specific application
    public function showApplication($id)
    {
        $application = ApplicationForm::with('user')->findOrFail($id);
        return view('admin.applications.show', compact('application'));
    }

    // ✅ Approve application
    public function approveApplication($id)
    {
        $application = ApplicationForm::with('user')->findOrFail($id);
        $application->status = 'approved';
        $application->remarks = null;
        $application->save();

        // 🔁 Create scholar record if not exists
        if (!Scholar::where('application_form_id', $application->application_form_id)->exists()) {
            Scholar::create([
                'user_id' => $application->user_id,
                'application_form_id' => $application->application_form_id,
                'status' => 'good_standing',
                'start_date' => now(),
            ]);
        }

        Mail::to($application->user->email)->send(new ApplicationStatusMail('approved'));

        return redirect()->route('admin.applications')->with('success', 'Application approved.');
    }

    // ✅ Reject application
    public function rejectApplication(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string'
        ]);

        $application = ApplicationForm::with('user')->findOrFail($id);
        $application->status = 'rejected';
        $application->remarks = $request->remarks;
        $application->save();

        Mail::to($application->user->email)->send(new ApplicationStatusMail('rejected', $request->remarks));

        return redirect()->route('admin.applications')->with('success', 'Application rejected.');
    }

    // ✅ Update status with optional remarks
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

        Mail::to($application->user->email)->send(new ApplicationStatusMail($request->status, $request->remarks));

        return redirect()->route('admin.applications.show', $id)
            ->with('success', 'Application status updated successfully!');
    }

    // ✅ Reports Summary View
    public function reportSummary(Request $request)
    {
        $total_applicants = ApplicationForm::count();
        $pending = ApplicationForm::where('status', 'pending')->count();
        $document_verification = ApplicationForm::where('status', 'document_verification')->count();
        $for_interview = ApplicationForm::where('status', 'for_interview')->count();
        $approved = ApplicationForm::where('status', 'approved')->count();
        $rejected = ApplicationForm::where('status', 'rejected')->count();

        $statusFilter = $request->query('status');

        $query = Scholar::with(['user', 'applicationForm']);
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // Only scholars whose related applicationForm is approved
        $query->whereHas('applicationForm', function ($q) {
            $q->where('status', 'approved');
        });

        $scholars = $query->latest()->paginate(10);

        return view('admin.reports.index', compact(
            'total_applicants',
            'pending',
            'document_verification',
            'for_interview',
            'approved',
            'rejected',
            'scholars',
            'statusFilter'
        ));
    }

    // ✅ PDF Report Download
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

    // ✅ View approved scholars using scholars table
    public function viewScholars()
    {
        $scholars = Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', function ($query) {
                $query->where('status', 'approved');
            })
            ->latest()
            ->paginate(5);

        return view('admin.scholars.index', compact('scholars'));
    }
}
