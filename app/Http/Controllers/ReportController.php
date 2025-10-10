<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ApplicationForm;
use App\Models\Scholar;
use App\Models\Evaluation;
use App\Models\ScholarMonitoring;
use App\Exports\ApplicantsExport;


class ReportController extends Controller
{
    public function index(Request $request)
{
    $type = $request->input('type', 'applicant');

    if ($type === 'scholar') {
        // ✅ Scholars tab – only scholars
        $records = Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', function ($q) {
                $q->where('status', 'approved');
            })
            ->get();
    } else {
        // ✅ Applicants tab – only applicants (excluding those already scholars)
        $records = ApplicationForm::with('user')
            ->whereDoesntHave('scholar') // prevent overlap with scholars
            ->orderBy('last_name')
            ->get();
    }

    // Monitoring + summaries
    $monitorings = ScholarMonitoring::with('scholar')->get();
    $statusSummary = ScholarMonitoring::selectRaw('status_code, COUNT(*) as total')
        ->groupBy('status_code')
        ->pluck('total', 'status_code');

    return view('admin.reports.index', [
        'type' => $type,
        'records' => $records,
        'monitorings' => $monitorings,
        'statusSummary' => $statusSummary,
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

   public function recentApplicants()
{
    $applicants = \App\Models\User::whereHas('role', function ($query) {
        $query->where('role_name', 'applicant');
    })->take(10)->get();

    return view('admin.reports.applicants', compact('applicants'));
}


public function Applicants(Request $request)
{
    // Get distinct and ordered academic years (latest first)
    $academicYears = \App\Models\ApplicationForm::select('academic_year')
        ->whereNotNull('academic_year')
        ->distinct()
        ->orderBy('academic_year', 'desc')
        ->pluck('academic_year');

    // Get distinct and ordered school terms (1st sem before 2nd sem)
    $schoolTerms = \App\Models\ApplicationForm::select('school_term')
        ->whereNotNull('school_term')
        ->distinct()
        ->orderBy('school_term', 'asc')
        ->pluck('school_term');

    // Capture filters
    $academicYear = $request->input('academic_year');
    $schoolTerm   = $request->input('school_term');

  
    // Base query — only real applicants that have filled out the form
$query = \App\Models\ApplicationForm::whereNotNull('first_name');


    // Apply filters if selected
    if ($academicYear) {
        $query->where('academic_year', $academicYear);
    }

    if ($schoolTerm) {
        $query->where('school_term', $schoolTerm);
    }

    // Retrieve applicants (sorted alphabetically)
    $applicants = $query->orderBy('last_name')->get();

    // Pass to the view
    return view('admin.reports.applicants', compact(
        'applicants', 'academicYears', 'schoolTerms', 'academicYear', 'schoolTerm'
    ));
}


public function monitoring()
{
    // All monitoring records (with linked scholar + user)
    $monitorings = ScholarMonitoring::with('scholar.user')->get();

    // Grouping used by the summary grid (degree_type => collection)
    $grouped = $monitorings->groupBy('degree_type');

    // All scholars (for the top "Monitoring of Scholars" table)
    $scholars = Scholar::with(['user', 'applicationForm', 'monitorings'])->get();

    return view('admin.reports.monitoring', compact('monitorings', 'grouped', 'scholars'));
}



public function downloadMonitoring()
{
    $grouped = ScholarMonitoring::all()->groupBy('degree_type');

    $pdf = Pdf::loadView('admin.reports.monitoring-pdf', compact('grouped'))->setPaper('legal', 'landscape');

    return $pdf->download('Scholarship_Monitoring_Report.pdf');
}

public function printMonitoring(Request $request)
{
    // Get filter parameters (School Term and AY)
    $schoolTerm = $request->input('school_term', '1st Semester');
    $academicYear = $request->input('academic_year', date('Y') . '-' . (date('Y') + 1));
    $program = $request->input('program', 'DOST');

    // Fetch grouped data
    $grouped = ScholarMonitoring::all()->groupBy('degree_type');

    // ✅ Filter scholars by program (via applicationForm)
    $scholars = Scholar::with(['user', 'applicationForm', 'monitorings'])
        ->when($program, function ($query) use ($program) {
            $query->whereHas('applicationForm', function ($sub) use ($program) {
                $sub->where('program', $program);
            });
        })
        // ✅ You can filter by academic year or school term if needed:
        ->when($schoolTerm, function ($query) use ($schoolTerm) {
            $query->whereHas('applicationForm', function ($sub) use ($schoolTerm) {
                $sub->where('school_term', $schoolTerm);
            });
        })
        ->when($academicYear, function ($query) use ($academicYear) {
            $query->whereHas('applicationForm', function ($sub) use ($academicYear) {
                $sub->where('academic_year', $academicYear);
            });
        })
        ->get();

    return view('admin.reports.monitoring-print', [
        'grouped' => $grouped,
        'scholars' => $scholars,
        'schoolTerm' => $schoolTerm,
        'academicYear' => $academicYear,
        'program' => $program,
    ]);
}


public function printAllApplicants(Request $request)
{
    // Filters (optional)
    $academicYear = $request->input('academic_year', null);
    $schoolTerm   = $request->input('school_term', null);

    // ✅ Base query: Only applicants (exclude those who became scholars)
    $query = ApplicationForm::with('user')
        ->whereDoesntHave('scholar'); // prevent overlap with scholars

    // ✅ Apply filters if provided
    if ($academicYear) {
        $query->where('academic_year', $academicYear);
    }

    if ($schoolTerm) {
        $query->where('school_term', $schoolTerm);
    }

    // ✅ Get filtered results ordered by name
    $applicants = $query->orderBy('last_name')->get();

    // For dropdown filters (distinct options)
    $academicYears = ApplicationForm::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');
    $schoolTerms   = ApplicationForm::select('school_term')->distinct()->pluck('school_term');

    // ✅ Return to the printable view
    return view('admin.reports.all-applicants-print', [
        'applicants'     => $applicants,
        'academicYears'  => $academicYears,
        'schoolTerms'    => $schoolTerms,
        'academicYear'   => $academicYear,
        'schoolTerm'     => $schoolTerm,
    ]);
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

public function saveApplicants(Request $request)
{
    $applicants = $request->input('applicants', []);

    foreach ($applicants as $data) {
        // Validate each applicant's data individually
        $validated = \Validator::make($data, [
            'address_no' => 'required|integer|min:1',
            'age' => 'nullable|integer|min:1',
            // add more validations as needed
        ])->validate();

        if (!empty($data['id'])) {
            $applicant = \App\Models\User::find($data['id']);
            if ($applicant) {
                $applicant->update($validated);
            }
        } else {
            \App\Models\User::create($validated);
        }
    }

    return back()->with('success', 'Applicants saved successfully.');
}



}
