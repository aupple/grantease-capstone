<?php

namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusMail;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ✅ Admin dashboard view
    public function dashboard()
    {
        $totalApplications = ApplicationForm::count();
        $approved = ApplicationForm::where('status', 'approved')->count();
        $pending = ApplicationForm::where('status', 'pending')->count();
        $rejected = ApplicationForm::where('status', 'rejected')->count();

        $total = ApplicationForm::count(); // Add this line
return view('admin.dashboard', compact('total', 'approved', 'pending', 'rejected'));
    }

    // ✅ View all submitted applications
    public function viewApplications(Request $request)
{
    $status = $request->query('status');

    $applications = ApplicationForm::with('user')
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->latest()
        ->get();

    return view('admin.applications.index', compact('applications', 'status'));
}

    // ✅ View a specific application
    public function showApplication($id)
    {
        $application = ApplicationForm::with('user')->findOrFail($id);
        return view('admin.applications.show', compact('application'));
    }

    // ✅ Approve an application
    public function approveApplication($id)
    {
        $application = ApplicationForm::with('user')->findOrFail($id);
        $application->status = 'approved';
        $application->remarks = null;
        $application->save();

        // ✉️ Send approval email
        Mail::to($application->user->email)->send(new ApplicationStatusMail('approved'));

        return redirect()->route('admin.applications.index')->with('success', 'Application approved.');
    }

    // ✅ Reject an application with remarks
    public function rejectApplication(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string',
        ]);

        $application = ApplicationForm::with('user')->findOrFail($id);
        $application->status = 'rejected';
        $application->remarks = $request->remarks;
        $application->save();

        // ✉️ Send rejection email with remarks
        Mail::to($application->user->email)->send(new ApplicationStatusMail('rejected', $request->remarks));

        return redirect()->route('admin.applications.index')->with('success', 'Application rejected.');
    }

    // ✅ View summary report of applications
    public function reportSummary()
    {
        return view('admin.reports.index', [
            'total'    => ApplicationForm::count(),
            'approved' => ApplicationForm::where('status', 'approved')->count(),
            'rejected' => ApplicationForm::where('status', 'rejected')->count(),
            'pending'  => ApplicationForm::where('status', 'pending')->count(),
        ]);
    }

    // ✅ Download PDF report
    public function downloadReportPdf()
    {
        $data = [
            'total'    => ApplicationForm::count(),
            'approved' => ApplicationForm::where('status', 'approved')->count(),
            'rejected' => ApplicationForm::where('status', 'rejected')->count(),
            'pending'  => ApplicationForm::where('status', 'pending')->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        return $pdf->download('application-report.pdf');
    }

    // ✅ View all approved scholars
    public function viewScholars()
    {
        $scholars = ApplicationForm::with('user')
            ->where('status', 'approved')
            ->whereNotNull('submitted_at')
            ->latest()
            ->get();

        return view('admin.scholars.index', compact('scholars'));
    }
}
