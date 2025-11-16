<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ApplicationForm;
use App\Models\Evaluation;
use App\Models\Enrollment;
use App\Models\ChedInfo;
use Illuminate\Support\Facades\Storage;
use App\Models\ChedGradeReport;
use App\Models\ChedEnrollmentReport;
use App\Models\ChedContinuingReport;

class ChedController extends Controller
{
    // Show CHED dashboard
   public function dashboard()
{
    $chedInfo = auth()->user()->chedInfo;
    
    return view('ched.dashboard', compact('chedInfo'));
}

    // =========================
    // Personal Information - VIEW PAGE (Read-only display)
    // =========================
    public function personalInformation()
    {
        $personalInfo = auth()->user()->chedInfo;
        
        if (!$personalInfo) {
            return redirect()
                ->route('ched.personal-form')
                ->with('info', 'Please complete your personal information first.');
        }
        
        return view('ched.personal-information', compact('personalInfo'));
    }
    
    public function personalForm()
    {
        // Get existing data if user has filled it before (for editing)
        $personalInfo = auth()->user()->chedInfo;
        
        return view('ched.personal-form', compact('personalInfo'));
    }

    public function storePersonalInformation(Request $request)
    {
        $validated = $request->validate([
            // Step 1 fields
            'academic_year' => 'required|string',
            'school_term' => 'required|string',
            'application_no' => 'required|string',
            'passport_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            
            // Step 2 - Personal Information
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            
            // Address
            'province' => 'required|string',
            'city' => 'required|string',
            'barangay' => 'required|string',
            'street' => 'required|string',
            'house_no' => 'nullable|string',
            'zip_code' => 'required|string',
            'region' => 'required|string',
            'district' => 'nullable|string',
            'passport_no' => 'nullable|string',
            
            // Contact Information
            'email' => 'required|email|max:255',
            'mailing_address' => 'nullable|string',
            'contact_no' => 'required|string|max:15',
            
            // Personal Details
            'civil_status' => 'required|string',
            'date_of_birth' => 'required|date',
            'age' => 'nullable|integer',
            'sex' => 'required|string',
            
            // Parents
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
        ]);

        try {
            // Handle passport photo upload
            if ($request->hasFile('passport_photo')) {
                // Delete old photo if exists
                $existingInfo = auth()->user()->chedInfo;
                if ($existingInfo && $existingInfo->passport_photo) {
                    Storage::disk('public')->delete($existingInfo->passport_photo);
                }
                
                $path = $request->file('passport_photo')->store('ched/passport_photos', 'public');
                $validated['passport_photo'] = $path;
            }

            // Add user_id
            $validated['user_id'] = auth()->id();

            // Create or update personal information
            $personalInfo = ChedInfo::updateOrCreate(
                ['user_id' => auth()->id()],
                $validated
            );

            // Mark personal info as completed in users table
            $user = auth()->user();
            $user->personal_info_completed = 1;
            $user->save();

            // Redirect to VIEW page (not dashboard)
            return redirect()
                ->route('ched.personal-information')
                ->with('success', 'Personal information saved successfully!');
                    
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save personal information: ' . $e->getMessage()]);
        }
    }

    // =========================
    // SIKAP DHEI Grade Report
    // =========================
    public function generateGradeReport()
    {
        $templatePath = storage_path('app/templates/sikap_grade.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Filter only CHED applications
        $applicants = ApplicationForm::with('evaluations')
            ->where('program', 'CHED')
            ->get();

        $row = 2;
        $i = 1;

        foreach ($applicants as $applicant) {
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $applicant->full_name);
            $sheet->setCellValue('C'.$row, $applicant->application_no);
            $sheet->setCellValue('D'.$row, $applicant->degree_program);
            $sheet->setCellValue('E'.$row, $applicant->evaluations->count());
            $sheet->setCellValue('F'.$row, $applicant->evaluations->where('status','Passed')->count());
            $sheet->setCellValue('G'.$row, $applicant->evaluations->where('status','Incomplete')->count());
            $sheet->setCellValue('H'.$row, $applicant->evaluations->where('status','Failed')->count());
            $sheet->setCellValue('I'.$row, $applicant->evaluations->where('status','No Grade')->count());
            $sheet->setCellValue('J'.$row, $applicant->evaluations->where('status','No Credited')->count());
            $sheet->setCellValue('K'.$row, $applicant->status);
            $sheet->setCellValue('L'.$row, $applicant->gpa);
            $sheet->setCellValue('M'.$row, $applicant->remarks);

            $row++;
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, 'SIKAP_DHEI_Grade_Report_'.date('Y-m-d').'.xlsx');
    }

    // =========================
    // SIKAP DHEI Enrollment Report (Sections A-D)
    // =========================
    public function generateEnrollmentReport()
    {
        $templatePath = storage_path('app/templates/sikap_enrollment.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $row = 2;
        $i = 1;

        // Section A: Enrolled Scholars, No Issues
        $sectionA = ApplicationForm::with('enrollments')
            ->where('program', 'CHED')
            ->where('enrollment_status', 'No Issues')
            ->get();
            
        foreach($sectionA as $applicant) {
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $applicant->full_name);
            $sheet->setCellValue('C'.$row, $applicant->application_number);
            $sheet->setCellValue('D'.$row, $applicant->degree_program);
            $sheet->setCellValue('E'.$row, $applicant->enrollment_status);
            $sheet->setCellValue('F'.$row, $applicant->enrollments->sum('units'));
            $sheet->setCellValue('G'.$row, $applicant->enrollments->where('re_enrolled', true)->count() > 0 ? 'Yes' : 'No');
            $sheet->setCellValue('H'.$row, $applicant->enrollment_remarks ?? '');
            $row++;
            $i++;
        }

        // Section B: Enrolled Scholars, But With Issues
        $sectionB = ApplicationForm::with('enrollments')
            ->where('program', 'CHED')
            ->where('enrollment_status', 'With Issues')
            ->get();
            
        foreach($sectionB as $applicant) {
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $applicant->full_name);
            $sheet->setCellValue('C'.$row, $applicant->application_number);
            $sheet->setCellValue('D'.$row, $applicant->degree_program);
            $sheet->setCellValue('E'.$row, $applicant->enrollment_status_detail ?? '');
            $sheet->setCellValue('F'.$row, $applicant->enrollment_others_status ?? '');
            $sheet->setCellValue('G'.$row, $applicant->enrollment_short_desc ?? '');
            $row++;
            $i++;
        }

        // Section C: Scholars Expected to Enroll, But Did Not Enroll
        $sectionC = ApplicationForm::with('enrollments')
            ->where('program', 'CHED')
            ->where('enrollment_status', 'Did Not Enroll')
            ->get();
            
        foreach($sectionC as $applicant) {
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $applicant->full_name);
            $sheet->setCellValue('C'.$row, $applicant->application_number);
            $sheet->setCellValue('D'.$row, $applicant->degree_program);
            $sheet->setCellValue('E'.$row, $applicant->enrollment_status_detail ?? '');
            $sheet->setCellValue('F'.$row, $applicant->enrollment_others_status ?? '');
            $sheet->setCellValue('G'.$row, $applicant->enrollment_short_desc ?? '');
            $row++;
            $i++;
        }

        // Section D: Scholars No Longer Expected to Enroll
        $sectionD = ApplicationForm::with('enrollments')
            ->where('program', 'CHED')
            ->where('enrollment_status', 'No Longer Expected')
            ->get();
            
        foreach($sectionD as $applicant) {
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $applicant->full_name);
            $sheet->setCellValue('C'.$row, $applicant->application_number);
            $sheet->setCellValue('D'.$row, $applicant->degree_program);
            $sheet->setCellValue('E'.$row, $applicant->enrollment_status_detail ?? '');
            $sheet->setCellValue('F'.$row, $applicant->enrollment_others_status ?? '');
            $sheet->setCellValue('G'.$row, $applicant->enrollment_short_desc ?? '');
            $row++;
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function() use ($writer){
            $writer->save('php://output');
        }, 'SIKAP_DHEI_Enrollment_Report_'.date('Y-m-d').'.xlsx');
    }

    // =========================
    // SIKAP Collated Continuing Eligibility Report
    // =========================
    public function generateContinuingEligibilityReport()
    {
        $templatePath = storage_path('app/templates/sikap_eligibility.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $row = 2;
        $i = 1;

        // Section A: Continuing Scholars
        $continuing = ApplicationForm::where('program', 'CHED')
            ->where('eligibility_status','Continuing')
            ->get();
            
        foreach($continuing as $applicant) {
            $sheet->setCellValue('A'.$row, $applicant->full_name);
            $sheet->setCellValue('B'.$row, $applicant->application_number);
            $sheet->setCellValue('C'.$row, $applicant->scholarship_type);
            $sheet->setCellValue('D'.$row, $applicant->degree_program);
            $sheet->setCellValue('E'.$row, $applicant->year_approval);
            $sheet->setCellValue('F'.$row, $applicant->last_term_enrolled);
            $sheet->setCellValue('G'.$row, $applicant->good_standing ? 'Yes' : 'No');
            $sheet->setCellValue('H'.$row, $applicant->good_standing ? '' : $applicant->standing_remark);
            $sheet->setCellValue('I'.$row, $applicant->expected_to_finish_on_time ? 'Yes' : 'No');
            $sheet->setCellValue('J'.$row, $applicant->expected_to_finish_on_time ? '' : $applicant->completion_remark);
            $sheet->setCellValue('K'.$row, $applicant->status_recommendation);
            $sheet->setCellValue('L'.$row, $applicant->recommendation_rationale);
            $row++;
            $i++;
        }

        // Section B: Graduated Scholars
        $graduates = ApplicationForm::where('program', 'CHED')
            ->where('eligibility_status','Graduated')
            ->get();
            
        foreach($graduates as $applicant){
            $sheet->setCellValue('A'.$row, $i);
            $sheet->setCellValue('B'.$row, $applicant->full_name);
            $sheet->setCellValue('C'.$row, $applicant->application_number);
            $sheet->setCellValue('D'.$row, $applicant->scholarship_type);
            $sheet->setCellValue('E'.$row, $applicant->degree_program);
            $sheet->setCellValue('F'.$row, $applicant->graduation_year);
            $sheet->setCellValue('G'.$row, $applicant->graduation_term);
            $sheet->setCellValue('H'.$row, $applicant->remarks);
            $row++;
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function() use ($writer){
            $writer->save('php://output');
        }, 'SIKAP_Continuing_Eligibility_Report_'.date('Y-m-d').'.xlsx');
    }


public function reports(Request $request)
{
    $query = DB::table('ched_info_table')
        ->leftJoin('ched_grade_reports', 'ched_info_table.id', '=', 'ched_grade_reports.ched_info_id')
        ->leftJoin('ched_enrollment_reports', 'ched_info_table.id', '=', 'ched_enrollment_reports.ched_info_id')
        ->leftJoin('ched_continuing_reports', 'ched_info_table.id', '=', 'ched_continuing_reports.ched_info_id')
        ->where('ched_info_table.status', 'approved');
    
    // Apply filters if present
    if ($request->filled('semester')) {
        $query->where('ched_info_table.school_term', $request->semester);
    }
    
    if ($request->filled('academic_year')) {
        $query->where('ched_info_table.academic_year', $request->academic_year);
    }
    
    $scholars = $query->select(
        'ched_info_table.id',
        'ched_info_table.last_name',
        'ched_info_table.first_name',
        'ched_info_table.middle_name',
        'ched_info_table.suffix',
        'ched_info_table.street',
        'ched_info_table.barangay',
        'ched_info_table.city',
        'ched_info_table.province',
        'ched_info_table.zip_code',
        'ched_info_table.district',
        'ched_info_table.region',
        'ched_info_table.email',
        'ched_info_table.date_of_birth',
        'ched_info_table.contact_no',
        'ched_info_table.sex',
        'ched_info_table.age',
        'ched_info_table.application_no',
        
        // Grade Report fields
        'ched_grade_reports.degree_program as grade_degree_program',
        'ched_grade_reports.enrolled_subjects',
        'ched_grade_reports.subjects_passed',
        'ched_grade_reports.incomplete_grades',
        'ched_grade_reports.subjects_failed',
        'ched_grade_reports.no_grades',
        'ched_grade_reports.not_credited_subjects',
        'ched_grade_reports.status as grade_status',
        'ched_grade_reports.gpa',
        'ched_grade_reports.remarks as grade_remarks',
        
        // Enrollment Report fields
        'ched_enrollment_reports.degree_program',
        'ched_enrollment_reports.enrollment_status',
        'ched_enrollment_reports.units_enrolled',
        'ched_enrollment_reports.retaken_subjects',
        'ched_enrollment_reports.remarks',
        'ched_enrollment_reports.issue_status',
        'ched_enrollment_reports.non_enrollment_status',
        'ched_enrollment_reports.termination_status',
        'ched_enrollment_reports.others_status',
        'ched_enrollment_reports.status_description',
        'ched_enrollment_reports.category', // ✅ Added comma here
        
        // Continuing report
        'ched_continuing_reports.scholarship_type',
        'ched_continuing_reports.year_of_approval',
        'ched_continuing_reports.last_term_enrollment',
        'ched_continuing_reports.good_academic_standing',
        'ched_continuing_reports.standing_explanation',
        'ched_continuing_reports.finish_on_time',
        'ched_continuing_reports.finish_explanation',
        'ched_continuing_reports.recommendation',
        'ched_continuing_reports.rationale',
        'ched_continuing_reports.academic_year_graduation',
        'ched_continuing_reports.term_of_graduation',
        'ched_continuing_reports.category as continuing_category'
    )
    ->orderBy('ched_info_table.last_name')
    ->get();
    
    // Separate scholars by enrollment category
    $scholars_enrollment_a = $scholars->where('category', 'a');
    $scholars_enrollment_b = $scholars->where('category', 'b');
    $scholars_enrollment_c = $scholars->where('category', 'c');
    $scholars_enrollment_d = $scholars->where('category', 'd');
    
    // Separate scholars by continuing category
    $scholars_continuing_a = $scholars->where('continuing_category', 'a');
    $scholars_continuing_b = $scholars->where('continuing_category', 'b');

    return view('admin.reports.ched-monitoring', compact(
        'scholars',
        'scholars_enrollment_a',
        'scholars_enrollment_b',
        'scholars_enrollment_c',
        'scholars_enrollment_d',
        'scholars_continuing_a',
        'scholars_continuing_b'
    ));
}
public function updateGradeReport(Request $request, $id)
{
    $validated = $request->validate([
        'degree_program' => 'nullable|string',
        'enrolled_subjects' => 'nullable|integer',
        'subjects_passed' => 'nullable|integer',
        'incomplete_grades' => 'nullable|integer',
        'subjects_failed' => 'nullable|integer',
        'no_grades' => 'nullable|integer',
        'not_credited_subjects' => 'nullable|integer',
        'status' => 'nullable|string',
        'gpa' => 'nullable|numeric|between:0,5',
        'remarks' => 'nullable|string',
    ]);

    ChedGradeReport::updateOrCreate(
        ['ched_info_id' => $id],
        $validated
    );

    return response()->json(['success' => true, 'message' => 'Grade report updated successfully']);
}

public function updateEnrollmentReport(Request $request, $id)
{
    try {
        $tableType = $request->table_type; // a, b, c, or d
        
        // Common validation
        $rules = [
            'degree_program' => 'nullable|string',
            'table_type' => 'required|in:a,b,c,d',
        ];
        
        // Table-specific validation
        if ($tableType === 'a') {
            $rules['enrollment_status'] = 'nullable|string';
            $rules['units_enrolled'] = 'nullable|integer';
            $rules['retaken_subjects'] = 'nullable|string';
            $rules['remarks'] = 'nullable|string';
        } elseif (in_array($tableType, ['b', 'c', 'd'])) {
            $rules['others_status'] = 'nullable|string';
            $rules['status_description'] = 'nullable|string';
            
            if ($tableType === 'b') {
                $rules['issue_status'] = 'nullable|string';
            } elseif ($tableType === 'c') {
                $rules['non_enrollment_status'] = 'nullable|string';
            } elseif ($tableType === 'd') {
                $rules['termination_status'] = 'nullable|string';
            }
        }
        
        $validated = $request->validate($rules);
        
        // Add category to the data
        $validated['category'] = $tableType;
        $validated['ched_info_id'] = $id;
        
        // Update or create enrollment report
        ChedEnrollmentReport::updateOrCreate(
            ['ched_info_id' => $id],
            $validated
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Enrollment report updated successfully'
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
public function addToEnrollment(Request $request, $id)
{
    try {
        $tableType = $request->table_type; // a, b, c, or d
        
        if (!in_array($tableType, ['a', 'b', 'c', 'd'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid table type'
            ], 400);
        }
        
        // Check if scholar exists
        $chedInfo = ChedInfo::findOrFail($id);
        
        // Check if already has enrollment report
        $existingReport = ChedEnrollmentReport::where('ched_info_id', $id)->first();
        
        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'This scholar already has an enrollment report'
            ], 400);
        }
        
        // Create new enrollment report
        ChedEnrollmentReport::create([
            'ched_info_id' => $id,
            'category' => $tableType,
            'degree_program' => null, // Will be filled manually
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Scholar added to enrollment report successfully'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
public function addToContinuing(Request $request, $id)
{
    try {
        $tableType = $request->table_type; // a or b
        
        if (!in_array($tableType, ['a', 'b'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid table type'
            ], 400);
        }
        
        $chedInfo = ChedInfo::findOrFail($id);
        
        $existingReport = ChedContinuingReport::where('ched_info_id', $id)->first();
        
        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'This scholar already has a continuing report'
            ], 400);
        }
        
        ChedContinuingReport::create([
            'ched_info_id' => $id,
            'category' => $tableType,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Scholar added to continuing report successfully'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function updateContinuingReport(Request $request, $id)
{
    try {
        $tableType = $request->table_type;
        
        $rules = [
            'scholarship_type' => 'nullable|string',
            'degree_program' => 'nullable|string',
            'table_type' => 'required|in:a,b',
        ];
        
        if ($tableType === 'a') {
            $rules['year_of_approval'] = 'nullable|string';
            $rules['last_term_enrollment'] = 'nullable|string';
            $rules['good_academic_standing'] = 'nullable|boolean';
            $rules['standing_explanation'] = 'nullable|string';
            $rules['finish_on_time'] = 'nullable|boolean';
            $rules['finish_explanation'] = 'nullable|string';
            $rules['recommendation'] = 'nullable|string';
            $rules['rationale'] = 'nullable|string';
        } elseif ($tableType === 'b') {
            $rules['academic_year_graduation'] = 'nullable|string';
            $rules['term_of_graduation'] = 'nullable|string';
            $rules['remarks'] = 'nullable|string';
        }
        
        $validated = $request->validate($rules);
        $validated['category'] = $tableType;
        $validated['ched_info_id'] = $id;
        
        ChedContinuingReport::updateOrCreate(
            ['ched_info_id' => $id],
            $validated
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Continuing report updated successfully'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}