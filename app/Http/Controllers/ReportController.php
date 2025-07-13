<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ApplicationForm;
use App\Models\Scholar;
use App\Models\Evaluation;
use App\Models\ScholarMonitoring;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'applicant');

        if ($type === 'scholar') {
            $records = Scholar::with(['user', 'applicationForm'])
                ->whereHas('applicationForm', function ($q) {
                    $q->where('status', 'approved');
                })->get();
        } else {
            $records = ApplicationForm::with('user')->where('status', 'pending')->get();
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
        $applicants = ApplicationForm::with('user')->paginate(10);
        return view('admin.reports.evaluation-index', compact('applicants'));
    }

    public function evaluationShow($id)
    {
        $applicant = ApplicationForm::with('user')->findOrFail($id);
        $evaluation = Evaluation::where('application_form_id', $id)->first();
        return view('admin.reports.evaluation-form', compact('applicant', 'evaluation'));
    }

    public function evaluationUpdate(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $application = ApplicationForm::findOrFail($id);
        $application->update([
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admin.reports.evaluation')->with('success', 'Remarks updated successfully.');
    }

    public function evaluationSave(Request $request, $id)
    {
        $validated = $request->validate([
            'remarks' => 'nullable|string',
            'program_study' => 'nullable|string',
            'specialization' => 'nullable|string',
            'scholarship_program' => 'nullable|string',
            'graduation_year' => 'nullable|string',
            'course_school' => 'nullable|string',
            'admission_program' => 'nullable|string',
            'admission_school' => 'nullable|string',
            'admission_date' => 'nullable|date',
            'academic_gpa' => 'nullable|string',
            'academic_honors' => 'nullable|string',
            'employer_deped' => 'nullable|string',
            'years_service' => 'nullable|string',
            'reg_deped' => 'nullable|string',
            'part_deped' => 'nullable|string',
            'performance_rating' => 'nullable|boolean',
            'physically_fit' => 'nullable|boolean',
            'health_comments' => 'nullable|string',
            'age' => 'nullable|integer',
            'endorsement_1' => 'nullable|string',
            'endorsement_2' => 'nullable|string',
            'grad_units' => 'nullable|string',
            'gpa_lateral' => 'nullable|string',
            'decision' => 'nullable|string',
            'evaluator_name' => 'nullable|string',
            'evaluation_date' => 'nullable|date',
        ]);

        Evaluation::updateOrCreate(
            ['application_form_id' => $id],
            array_merge($validated, [
                'evaluator_id' => auth()->user()->user_id,
            ])
        );

        return redirect()->route('admin.reports.evaluation.show', $id)
            ->with('success', 'Evaluation sheet saved successfully!');
    }

    public function applicants()
    {
        return view('admin.reports.applicants');
    }


public function monitoring()
{
    $grouped = ScholarMonitoring::all()->groupBy('degree_type');
    return view('admin.reports.monitoring', compact('grouped'));
}

public function downloadMonitoring()
{
    $grouped = ScholarMonitoring::all()->groupBy('degree_type');

    $pdf = Pdf::loadView('admin.reports.monitoring-pdf', compact('grouped'))->setPaper('legal', 'landscape');

    return $pdf->download('Scholarship_Monitoring_Report.pdf');
}

    public function scoresheets()
    {
        $applicants = ApplicationForm::with('user')->paginate(10);
        return view('admin.reports.scoresheets', compact('applicants'));
    }

    public function scoresheetShow($id)
    {
        $applicant = ApplicationForm::with('user')->findOrFail($id);
        return view('admin.reports.scoresheet-show', compact('applicant'));
    }

    public function exportSelected(Request $request)
    {
        $type = $request->input('type');
        $selectedIds = $request->input('selected', []);

        if (empty($selectedIds) || !in_array($type, ['applicant', 'scholar'])) {
            return redirect()->back()->with('error', 'Invalid selection or type.');
        }

        $records = $type === 'applicant'
            ? ApplicationForm::with('user')->whereIn('application_form_id', $selectedIds)->get()
            : Scholar::with(['user', 'applicationForm'])->whereIn('id', $selectedIds)->get();

        $pdf = Pdf::loadView('admin.reports.export-selected', [
            'records' => $records,
            'type' => $type,
        ]);

        return $pdf->download("selected-{$type}s-records.pdf");
    }
    public function saveMonitoring(Request $request)
{
    $data = $request->input('data');

    foreach ($data as $degree => $years) {
        foreach ($years as $year => $statuses) {
            foreach ($statuses as $code => $count) {
                if ($count > 0) {
                    ScholarMonitoring::updateOrCreate(
                        [
                            'degree_type' => $degree,
                            'year_awarded' => $year,
                            'status_code' => $code,
                        ],
                        ['total' => $count]
                    );
                }
            }
        }
    }

    return redirect()->back()->with('success', 'Monitoring data saved!');
}


}
