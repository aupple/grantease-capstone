<?php

namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusMail;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ✅ View all submitted applications
    public function viewApplications()
    {
        $applications = ApplicationForm::with('user')->latest()->get();
        return view('admin.applications.index', compact('applications'));
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

    // ✉️ Send email
    Mail::to($application->user->email)->send(new ApplicationStatusMail('approved'));

    return redirect()->route('admin.applications')->with('success', 'Application approved.');
}

// ✅ Reject application with remarks
public function rejectApplication(Request $request, $id)
{
    $request->validate([
        'remarks' => 'required|string'
    ]);

    $application = ApplicationForm::with('user')->findOrFail($id);
    $application->status = 'rejected';
    $application->remarks = $request->remarks;
    $application->save();

    // ✉️ Send email
    Mail::to($application->user->email)->send(new ApplicationStatusMail('rejected', $request->remarks));

    return redirect()->route('admin.applications')->with('success', 'Application rejected.');
}
public function reportSummary()
{
    return view('admin.reports.index', [
        'total' => ApplicationForm::count(),
        'approved' => ApplicationForm::where('status', 'approved')->count(),
        'rejected' => ApplicationForm::where('status', 'rejected')->count(),
        'pending' => ApplicationForm::where('status', 'pending')->count(),
    ]);
}
public function downloadReportPdf()
{
    $data = [
        'total' => ApplicationForm::count(),
        'approved' => ApplicationForm::where('status', 'approved')->count(),
        'rejected' => ApplicationForm::where('status', 'rejected')->count(),
        'pending' => ApplicationForm::where('status', 'pending')->count(),
    ];

    $pdf = Pdf::loadView('admin.reports.pdf', $data);
    return $pdf->download('application-report.pdf');
}
public function viewScholars()
{
    $scholars = ApplicationForm::with('user')
        ->where('status', 'approved')
        ->whereNotNull('submitted_at') // ✅ Only show actually submitted and approved
        ->latest()
        ->get();

    return view('admin.scholars.index', compact('scholars'));
}


}
