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
  public function viewApplications(Request $request)
{
    $status = $request->query('status');
    $search = $request->query('search');

    $applications = ApplicationForm::with('user')
        ->when($status, fn($query) => $query->where('status', $status))
        ->when($search, function ($query, $search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "$search%")    // 👈 begins with
                      ->orWhere('middle_name', 'like', "$search%")
                      ->orWhere('last_name', 'like', "$search%");
                })
                ->orWhere('program', 'like', "$search%")
                ->orWhere('status', 'like', "$search%");
            });
        })
        ->latest()
        ->get(); // Or paginate()

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
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:submitted,under_review,document_verification,for_interview,approved,rejected',
        'remarks' => 'nullable|string',
    ]);

    $application = ApplicationForm::with('user')->findOrFail($id);
    $application->status = $request->status;
    $application->remarks = $request->remarks;
    $application->save();

    // Optionally send email
    Mail::to($application->user->email)->send(new ApplicationStatusMail($request->status, $request->remarks));

    return redirect()->route('admin.applications.show', $id)
        ->with('success', 'Application status updated successfully!');
}


public function reportSummary()
{
    return view('admin.reports.index', [
        'total' => ApplicationForm::count(),
        'submitted' => ApplicationForm::where('status', 'submitted')->count(),
        'under_review' => ApplicationForm::where('status', 'under_review')->count(),
        'document_verification' => ApplicationForm::where('status', 'document_verification')->count(),
        'for_interview' => ApplicationForm::where('status', 'for_interview')->count(),
        'approved' => ApplicationForm::where('status', 'approved')->count(),
        'rejected' => ApplicationForm::where('status', 'rejected')->count(),
    ]);
}

public function downloadReportPdf()
{
    $data = [
        'total' => ApplicationForm::count(),
        'submitted' => ApplicationForm::where('status', 'submitted')->count(),
        'under_review' => ApplicationForm::where('status', 'under_review')->count(),
        'document_verification' => ApplicationForm::where('status', 'document_verification')->count(),
        'for_interview' => ApplicationForm::where('status', 'for_interview')->count(),
        'approved' => ApplicationForm::where('status', 'approved')->count(),
        'rejected' => ApplicationForm::where('status', 'rejected')->count(),
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
