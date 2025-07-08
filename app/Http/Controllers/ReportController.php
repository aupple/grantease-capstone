<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ApplicationForm;
use App\Models\Scholar;

class ReportController extends Controller
{
   public function index(Request $request)
{
    $type = $request->input('type', 'applicant'); // default to 'applicant'

    if ($type === 'scholar') {
        $records = Scholar::with('user')->get();
    } else {
        $records = ApplicationForm::with('user')->get();
    }

    return view('admin.reports.index', [
        'type' => $type,
        'records' => $records,
        'total_applicants' => ApplicationForm::count(),
        'approved' => ApplicationForm::where('status', 'approved')->count(),
        'rejected' => ApplicationForm::where('status', 'rejected')->count(),
        'pending' => ApplicationForm::where('status', 'pending')->count(),
    ]);
}


    public function evaluation()
    {
        return view('admin.reports.evaluation');
    }

    public function applicants()
    {
        return view('admin.reports.applicants');
    }

    public function scholars()
    {
        return view('admin.reports.scholars');
    }

    public function scoresheets()
    {
        return view('admin.reports.scoresheets');
    }

    // ✅ Export selected applicants or scholars as PDF
    public function exportSelected(Request $request)
    {
        $type = $request->input('type'); // 'applicant' or 'scholar'
        $selectedIds = $request->input('selected', []);

        if (empty($selectedIds) || !in_array($type, ['applicant', 'scholar'])) {
            return redirect()->back()->with('error', 'Invalid selection or type.');
        }

        if ($type === 'applicant') {
            $records = ApplicationForm::with('user')
                ->whereIn('id', $selectedIds)
                ->get();
        } else {
            $records = Scholar::with(['user', 'applicationForm'])
                ->whereIn('id', $selectedIds)
                ->get();
        }

        $pdf = Pdf::loadView('admin.reports.export-selected', [
            'records' => $records,
            'type' => $type,
        ]);

        return $pdf->download("selected-{$type}s-records.pdf");
    }
}
