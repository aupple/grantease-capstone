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

        // Base query — only applicants that have filled out the form AND are NOT approved scholars
        $query = \App\Models\ApplicationForm::whereNotNull('first_name')
            ->whereDoesntHave('scholar'); // <-- exclude approved scholars

        // Apply filters if selected
        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($schoolTerm) {
            $query->where(function ($q) use ($schoolTerm) {
                $q->where('school_term', $schoolTerm)
                    ->orWhere('school_term', 'LIKE', "%{$schoolTerm}%");
            });
        }

        // Retrieve applicants (sorted alphabetically)
        $applicants = $query->orderBy('last_name')->get();

        // Pass to the view
        return view('admin.reports.applicants', compact(
            'applicants',
            'academicYears',
            'schoolTerms',
            'academicYear',
            'schoolTerm'
        ));
    }


    public function monitoring(Request $request)
    {
        $semester = $request->input('semester', null);
        $academicYear = $request->input('academic_year', null);

        // Fetch all approved scholars with optional filters
        $scholars = Scholar::with('applicationForm')
            ->whereHas('applicationForm', function ($q) use ($semester, $academicYear) {
                $q->where('status', 'approved');

                if ($semester) {
                    $q->where('school_term', $semester);
                }
                if ($academicYear) {
                    $q->where('academic_year', $academicYear);
                }
            })
            ->get();

        // Pass directly to the Blade
        return view('admin.reports.monitoring', compact('scholars'));
    }



    public function showMonitoring()
    {
        // Fetch all approved scholars
        $scholars = Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', fn($q) => $q->where('status', 'approved'))
            ->get();

        return view('admin.reports.monitoring', compact('scholars'));
    }

    public function updateMonitoringField(Request $request)
    {
        $data = $request->all(); 
        $createdIds = [];

        foreach ($data as $item) {
            if (!in_array($item['field'], [
                'course',
                'school',
                'enrollment_type',
                'scholarship_duration',
                'date_started',
                'expected_completion',
                'remarks'
            ])) {
                continue;
            }

            // ✅ Find existing record for this scholar
            $monitoring = null;

            if (!empty($item['id'])) {
                $monitoring = \App\Models\ScholarMonitoring::find($item['id']);
            }

            if (!$monitoring) {
                $monitoring = \App\Models\ScholarMonitoring::where('scholar_id', $item['scholar_id'])->first();
            }

            // ✅ Create only once if no record exists yet
            if (!$monitoring) {
                $monitoring = new \App\Models\ScholarMonitoring();
                $monitoring->scholar_id = $item['scholar_id'];
            }

            // ✅ Update field value
            $monitoring->{$item['field']} = $item['value'];
            $monitoring->save();

            $createdIds[] = $monitoring->id;
        }

        return response()->json([
            'success' => true,
            'monitoring_ids' => $createdIds,
        ]);
    }




    public function printMonitoring(Request $request)
    {
        $schoolTerm = $request->input('school_term', null);
        $academicYear = $request->input('academic_year', null);
        $program = $request->input('program', null);

        $scholars = Scholar::with(['user', 'applicationForm'])
            ->whereHas('applicationForm', function ($q) use ($schoolTerm, $academicYear, $program) {
                $q->where('status', 'approved');

                if ($schoolTerm) $q->where('school_term', $schoolTerm);
                if ($academicYear) $q->where('academic_year', $academicYear);
                if ($program) $q->where('program', $program);
            })
            ->get();

        return view('admin.reports.monitoring-print', compact('scholars', 'schoolTerm', 'academicYear', 'program'));
    }

    public function chedMonitoring()
    {
        // CHED scholars only
        $scholars = Scholar::with(['user', 'applicationForm', 'monitorings'])
            ->where('program', 'CHED')
            ->get();

        $monitorings = ScholarMonitoring::with('scholar.user')
            ->whereIn('scholar_id', $scholars->pluck('id'))
            ->get();

        $grouped = $monitorings->groupBy('degree_type');

        return view('admin.reports.ched-monitoring', compact('monitorings', 'grouped', 'scholars'));
    }


    public function downloadMonitoring()
    {
        $grouped = ScholarMonitoring::all()->groupBy('degree_type');

        $pdf = Pdf::loadView('admin.reports.monitoring-pdf', compact('grouped'))->setPaper('legal', 'landscape');

        return $pdf->download('Scholarship_Monitoring_Report.pdf');
    }



    public function printallApplicants(Request $request)
    {
        $academicYear = $request->input('academic_year', null);
        $schoolTerm   = $request->input('school_term', null);
        $selectedCols = $request->input('cols') ? json_decode($request->input('cols'), true) : [];

        // Get applicants who are not yet scholars
        $query = ApplicationForm::with('user')->whereDoesntHave('scholar');

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($schoolTerm) {
            $query->where('school_term', $schoolTerm);
        }

        $applicants = $query->orderBy('last_name')->get();

        return view('admin.reports.all-applicants-print', [
            'applicants'   => $applicants,
            'selectedCols' => $selectedCols,
            'academicYear' => $academicYear,
            'schoolTerm'   => $schoolTerm,
        ]);
    }




    public function updateField(Request $request)
    {
        $data = $request->all(); // expects array of {id, field, value}

        foreach ($data as $item) {
            $applicant = ApplicationForm::find($item['id']);
            if ($applicant && in_array($item['field'], ['thesis_title', 'units_required', 'duration', 'intended_degree', 'remarks'])) {
                $applicant->{$item['field']} = $item['value'];
                $applicant->save();
            }
        }

        return response()->json(['success' => true]);
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
