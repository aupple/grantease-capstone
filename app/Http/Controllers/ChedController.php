<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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
use App\Helpers\ActivityLogger;

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
    // ========================

public function personalInformation()
{
    $personalInfo = auth()->user()->chedInfo;

    if (!$personalInfo) {
        return redirect()
            ->route('ched.personal-form')
            ->with('info', 'Please complete your personal information first.');
    }

    // Helper function to fetch name with caching and timeout
    $fetchPSGCName = function ($type, $code) {
        if (!$code) return '';

        return Cache::remember("psgc_{$type}_{$code}", 3600, function () use ($type, $code) {
            try {
                $url = match($type) {
                    'province' => "https://psgc.gitlab.io/api/provinces/{$code}/",
                    'city'     => "https://psgc.gitlab.io/api/cities-municipalities/{$code}/",
                    'barangay' => "https://psgc.gitlab.io/api/barangays/{$code}/",
                };

                $response = Http::timeout(10)->get($url);

                if ($response->successful()) {
                    return $response->json()['name'] ?? '';
                }
            } catch (\Exception $e) {
                // Optional: log the error
                \Log::error("PSGC fetch failed for {$type} code {$code}: ".$e->getMessage());
            }

            return '';
        });
    };

    $provinceName = $fetchPSGCName('province', $personalInfo->province);
    $cityName     = $fetchPSGCName('city', $personalInfo->city);
    $barangayName = $fetchPSGCName('barangay', $personalInfo->barangay);

    return view('ched.personal-information', compact(
        'personalInfo',
        'provinceName',
        'cityName',
        'barangayName'
    ));
}
    
    public function personalForm()
    {
        ActivityLogger::log('VIEW_APPLICATION', 'Viewed own application');

        $personalInfo = auth()->user()->chedInfo;

        if ($personalInfo) {
        ActivityLogger::log(
            'CHED_PERSONAL_FORM_VIEWED', 
            'Viewed CHED personal information form | CHED Info ID: ' . $personalInfo->id
        );
    } else {
        ActivityLogger::log(
            'CHED_PERSONAL_FORM_VIEWED', 
            'Viewed empty CHED personal information form (new submission)'
        );
    }
        
        return view('ched.personal-form', compact('personalInfo'));
    }

    public function storePersonalInformation(Request $request)
    {

        $validated = $request->validate([
            // Step 1 fields
            'academic_year' => 'required|string',
            'school_term' => 'required|string',
            'school' => 'required|string|max:255',
            'year_level' => 'required|string',
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

             $action = $personalInfo->wasRecentlyCreated ? 'CHED_PERSONAL_INFO_SUBMITTED' : 'CHED_PERSONAL_INFO_UPDATED';
        ActivityLogger::log(
            $action, 
            'CHED Info ID: ' . $personalInfo->id . ' | Name: ' . $personalInfo->getFullNameAttribute() . ' | Application No: ' . $personalInfo->application_no
        );

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
        'ched_enrollment_reports.category',
        
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
    
    // ✅ Convert PSGC codes to readable names
    $scholars = $scholars->map(function ($scholar) {
        // Helper function to fetch PSGC name
        $fetchPSGCName = function ($type, $code) {
            if (!$code) return '';

            return Cache::remember("psgc_{$type}_{$code}", 3600, function () use ($type, $code) {
                try {
                    $url = match($type) {
                        'province' => "https://psgc.gitlab.io/api/provinces/{$code}/",
                        'city'     => "https://psgc.gitlab.io/api/cities-municipalities/{$code}/",
                        'barangay' => "https://psgc.gitlab.io/api/barangays/{$code}/",
                    };

                    $response = Http::timeout(10)->get($url);

                    if ($response->successful()) {
                        return $response->json()['name'] ?? '';
                    }
                } catch (\Exception $e) {
                    \Log::error("PSGC fetch failed for {$type} code {$code}: ".$e->getMessage());
                }

                return $code; // Return code if fetch fails
            });
        };

        // Convert codes to names
        $scholar->province = $fetchPSGCName('province', $scholar->province);
        $scholar->city = $fetchPSGCName('city', $scholar->city);
        $scholar->barangay = $fetchPSGCName('barangay', $scholar->barangay);

        return $scholar;
    });
    
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

public function exportToExcel(Request $request)
{
    $reportType = $request->input('report_type', 'personal');
    $semester = $request->input('semester');
    $academicYear = $request->input('academic_year');
    $hiddenColumns = $request->input('hidden_columns', []);

    // Map report types to template files
    $templates = [
        'gradereport' => 'SIKAP DHEI Grade Report.xlsx',
        'enrollment' => 'SIKAP DHEI Enrollment Report.xlsx',
        'continuing' => 'SIKAP Collated Continuing Eligibility Report.xlsx',
    ];

    $templatePath = storage_path('app/templates/' . $templates[$reportType]);

    if (!file_exists($templatePath)) {
        return response()->json(['error' => 'Template file not found: ' . $templates[$reportType]], 404);
    }

    try {
        // Load the template
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Build query with filters
        $query = DB::table('ched_info_table');

        // Join based on report type
        if ($reportType === 'gradereport') {
            $query->leftJoin('ched_grade_reports', 'ched_info_table.id', '=', 'ched_grade_reports.ched_info_id');
        } elseif ($reportType === 'enrollment') {
            $query->leftJoin('ched_enrollment_reports', 'ched_info_table.id', '=', 'ched_enrollment_reports.ched_info_id');
        } elseif ($reportType === 'continuing') {
            $query->leftJoin('ched_continuing_reports', 'ched_info_table.id', '=', 'ched_continuing_reports.ched_info_id');
        }

        // Apply filters
        $query->where('ched_info_table.status', 'approved');

        if ($semester && $semester !== 'all') {
            $query->where('ched_info_table.school_term', $semester);
        }

        if ($academicYear && $academicYear !== 'all') {
            $query->where('ched_info_table.academic_year', $academicYear);
        }

        // Select columns based on report type
        if ($reportType === 'gradereport') {
            $scholars = $query->select(
                'ched_info_table.id',
                'ched_info_table.first_name',
                'ched_info_table.middle_name',
                'ched_info_table.last_name',
                'ched_info_table.suffix',
                'ched_info_table.application_no',
                
                // From ched_grade_reports table
                'ched_grade_reports.degree_program',
                'ched_grade_reports.enrolled_subjects',
                'ched_grade_reports.subjects_passed',
                'ched_grade_reports.incomplete_grades',
                'ched_grade_reports.subjects_failed',
                'ched_grade_reports.no_grades',
                'ched_grade_reports.not_credited_subjects',
                'ched_grade_reports.status',
                'ched_grade_reports.gpa',
                'ched_grade_reports.remarks'
            )->get();
        } elseif ($reportType === 'enrollment') {
            $scholars = $query->select(
                'ched_info_table.id',
                'ched_info_table.first_name',
                'ched_info_table.middle_name',
                'ched_info_table.last_name',
                'ched_info_table.suffix',
                'ched_info_table.application_no',
                
                'ched_enrollment_reports.*'
            )->get();
        } elseif ($reportType === 'continuing') {
            $scholars = $query->select(
                'ched_info_table.id',
                'ched_info_table.first_name',
                'ched_info_table.middle_name',
                'ched_info_table.last_name',
                'ched_info_table.suffix',
                'ched_info_table.application_no',
                
                'ched_continuing_reports.*'
            )->get();
        }

        // DEBUG: Check if data exists
        if ($scholars->isEmpty()) {
            return response()->json(['error' => 'No scholars found with the selected filters'], 404);
        }

        // Populate based on report type
        switch ($reportType) {
            case 'gradereport':
                $this->populateGradeReport($sheet, $scholars, $hiddenColumns);
                $filename = 'SIKAP_Grade_Report_' . date('Y-m-d') . '.xlsx';
                break;
                
            case 'enrollment':
                $this->populateEnrollmentReport($spreadsheet, $scholars, $hiddenColumns);
                $filename = 'SIKAP_Enrollment_Report_' . date('Y-m-d') . '.xlsx';
                break;
                
            case 'continuing':
                $this->populateContinuingReport($spreadsheet, $scholars, $hiddenColumns);
                $filename = 'SIKAP_Continuing_Report_' . date('Y-m-d') . '.xlsx';
                break;
        }

        // Save and download
        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
    }
}

// Helper method for Grade Report
private function populateGradeReport($sheet, $scholars, $hiddenColumns)
{
    $row = 6;
    $no = 1;

    foreach ($scholars as $scholar) {
        $col = 'A'; // Start from column A
        
        if (!in_array('gr_no', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $no);
        }
        if (!in_array('gr_name', $hiddenColumns)) {
            $fullName = trim(($scholar->first_name ?? '') . ' ' . 
                            ($scholar->middle_name ?? '') . ' ' . 
                            ($scholar->last_name ?? '') . ' ' . 
                            ($scholar->suffix ?? ''));
            $sheet->setCellValue($col++ . $row, $fullName);
        }
        if (!in_array('application_no', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->application_no ?? '');
        }
        if (!in_array('gr_degree_program', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->degree_program ?? '');
        }
        if (!in_array('gr_enrolled_subjects', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->enrolled_subjects ?? '');
        }
        if (!in_array('gr_subjects_passed', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->subjects_passed ?? '');
        }
        if (!in_array('gr_incomplete_grades', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->incomplete_grades ?? '');
        }
        if (!in_array('gr_subjects_failed', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->subjects_failed ?? '');
        }
        if (!in_array('gr_no_grades', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->no_grades ?? '');
        }
        if (!in_array('gr_not_credited', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->not_credited_subjects ?? '');
        }
        if (!in_array('gr_status', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->status ?? '');
        }
        if (!in_array('gr_gpa', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, 
                $scholar->gpa ? number_format($scholar->gpa, 2) : ''
            );
        }
        if (!in_array('gr_remarks', $hiddenColumns)) {
            $sheet->setCellValue($col++ . $row, $scholar->remarks ?? '');
        }

        $row++;
        $no++;
    }

    $lastRow = $row - 1;
    $sheet->getStyle('A6:M' . $lastRow)->getFont()->getColor()->setARGB('FF000000');

    $sheet->getColumnDimension('A')->setWidth(5);   // No.
    $sheet->getColumnDimension('B')->setWidth(20);  // Name
    $sheet->getColumnDimension('C')->setWidth(18);  // Application Number
    $sheet->getColumnDimension('D')->setWidth(25);  // Degree Program
    $sheet->getColumnDimension('E')->setWidth(10);  // Enrolled Subjects
    $sheet->getColumnDimension('F')->setWidth(10);  // Subjects Passed
    $sheet->getColumnDimension('G')->setWidth(12);  // Incomplete Grades
    $sheet->getColumnDimension('H')->setWidth(10);  // Subjects Failed
    $sheet->getColumnDimension('I')->setWidth(10);  // No Grades
    $sheet->getColumnDimension('J')->setWidth(12);  // Not Credited
    $sheet->getColumnDimension('K')->setWidth(15);  // Status
    $sheet->getColumnDimension('L')->setWidth(12);  // GPA
    $sheet->getColumnDimension('M')->setWidth(40);  // Remarks (wider for text)
    
    // Enable text wrapping for Remarks column
    $sheet->getStyle('M6:M' . ($row - 1))->getAlignment()->setWrapText(true);
}

// Replace your enrollment methods with these updated versions

private function populateEnrollmentReport($spreadsheet, $scholars, $hiddenColumns)
{
    // Use ONLY the active sheet
    $sheet = $spreadsheet->getActiveSheet();
    
    // DEBUG: Log what we're getting
    \Log::info('Total scholars for enrollment: ' . $scholars->count());
    \Log::info('Category A count: ' . $scholars->where('category', 'a')->count());
    \Log::info('Category B count: ' . $scholars->where('category', 'b')->count());
    \Log::info('Category C count: ' . $scholars->where('category', 'c')->count());
    \Log::info('Category D count: ' . $scholars->where('category', 'd')->count());
    
    // Table A: Enrolled Scholars, With No Issues (Row 8 based on template)
    $currentRow = 11; 
    $currentRow = $this->populateEnrollmentTableA($sheet, $scholars->where('category', 'a'), $hiddenColumns, $currentRow);
    
    // ✅ Table B: Starts where Table A ended + spacing for headers
    $currentRow += 3; // Add space for Table B header (adjust based on your template)
    $currentRow = $this->populateEnrollmentTableB($sheet, $scholars->where('category', 'b'), $hiddenColumns, $currentRow);
    
    // ✅ Table C: Starts where Table B ended + spacing for headers
    $currentRow += 3; // Add space for Table C header (adjust based on your template)
    $currentRow = $this->populateEnrollmentTableC($sheet, $scholars->where('category', 'c'), $hiddenColumns, $currentRow);
    
    // ✅ Table D: Starts where Table C ended + spacing for headers
    $currentRow += 3; // Add space for Table D header (adjust based on your template)
    $currentRow = $this->populateEnrollmentTableD($sheet, $scholars->where('category', 'd'), $hiddenColumns, $currentRow);
}

private function populateEnrollmentTableA($sheet, $scholars, $hiddenColumns, $startRow)
{
    \Log::info('Table A - Starting at row: ' . $startRow . ' with ' . $scholars->count() . ' scholars');
    
    $row = $startRow;
    $no = 1;
    $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']; // ✅ Column letters
    
    foreach ($scholars as $scholar) {
        \Log::info('Processing scholar: ' . $scholar->first_name . ' ' . $scholar->last_name);
        
        $colIndex = 0; // ✅
        
        if (!in_array('enr_a_no', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $no); // ✅ Uses 'A8', 'B8' etc
        }
        $colIndex++;
        
        if (!in_array('enr_a_name', $hiddenColumns)) {
            $fullName = trim(($scholar->first_name ?? '') . ' ' . 
                            ($scholar->middle_name ?? '') . ' ' . 
                            ($scholar->last_name ?? '') . ' ' . 
                            ($scholar->suffix ?? ''));
            $sheet->setCellValue($columns[$colIndex] . $row, $fullName);
        }
        $colIndex++;
        
        if (!in_array('enr_a_application_number', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->application_no ?? '');
        }
        $colIndex++;
        
        if (!in_array('enr_a_degree_program', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->degree_program ?? '');
        }
        $colIndex++;
        
        if (!in_array('enr_a_status', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->enrollment_status ?? '');
        }
        $colIndex++;
        
        if (!in_array('enr_a_units_enrolled', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->units_enrolled ?? '');
        }
        $colIndex++;
        
        if (!in_array('enr_a_retaken_subjects', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->retaken_subjects ?? '');
        }
        $colIndex++;
        
        if (!in_array('enr_a_remarks', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->remarks ?? '');
        }

        $row++;
        $no++;
    }
    
    \Log::info('Table A - Ended at row: ' . $row);
    return $row;
}

private function populateEnrollmentTableB($sheet, $scholars, $hiddenColumns, $startRow)
{
    $row = $startRow;
    $no = 1;
    
    foreach ($scholars as $scholar) {
        $colIndex = 1;
        
        if (!in_array('enr_b_no', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $no);
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_b_name', $hiddenColumns)) {
            $fullName = trim(($scholar->first_name ?? '') . ' ' . 
                            ($scholar->middle_name ?? '') . ' ' . 
                            ($scholar->last_name ?? '') . ' ' . 
                            ($scholar->suffix ?? ''));
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $fullName);
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_b_application_number', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->application_no ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_b_degree_program', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->enrollment_degree_program ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_b_status', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->issue_status ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_b_others_status', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->others_status ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_b_description', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->status_description ?? '');
        } else {
            $colIndex++;
        }

        $row++;
        $no++;
    }
    
    return $row;
}

private function populateEnrollmentTableC($sheet, $scholars, $hiddenColumns, $startRow)
{
    $row = $startRow;
    $no = 1;
    
    foreach ($scholars as $scholar) {
        $colIndex = 1;
        
        if (!in_array('enr_c_no', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $no);
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_c_name', $hiddenColumns)) {
            $fullName = trim(($scholar->first_name ?? '') . ' ' . 
                            ($scholar->middle_name ?? '') . ' ' . 
                            ($scholar->last_name ?? '') . ' ' . 
                            ($scholar->suffix ?? ''));
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $fullName);
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_c_application_number', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->application_no ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_c_degree_program', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->enrollment_degree_program ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_c_status', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->non_enrollment_status ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_c_others_status', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->others_status ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_c_description', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->status_description ?? '');
        } else {
            $colIndex++;
        }

        $row++;
        $no++;
    }
    
    return $row;
}

private function populateEnrollmentTableD($sheet, $scholars, $hiddenColumns, $startRow)
{
    $row = $startRow;
    $no = 1;
    
    foreach ($scholars as $scholar) {
        $colIndex = 1;
        
        if (!in_array('enr_d_no', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $no);
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_d_name', $hiddenColumns)) {
            $fullName = trim(($scholar->first_name ?? '') . ' ' . 
                            ($scholar->middle_name ?? '') . ' ' . 
                            ($scholar->last_name ?? '') . ' ' . 
                            ($scholar->suffix ?? ''));
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $fullName);
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_d_application_number', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->application_no ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_d_degree_program', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->enrollment_degree_program ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_d_status', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->termination_status ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_d_others_status', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->others_status ?? '');
        } else {
            $colIndex++;
        }
        
        if (!in_array('enr_d_description', $hiddenColumns)) {
            $sheet->setCellValueByColumnAndRow($colIndex++, $row, $scholar->status_description ?? '');
        } else {
            $colIndex++;
        }

        $row++;
        $no++;
    }
    
    return $row;
}

// Helper method for Continuing Report
private function populateContinuingReport($spreadsheet, $scholars, $hiddenColumns)
{
    $sheet = $spreadsheet->getActiveSheet();
    
    // DEBUG: Log what we're getting
    \Log::info('=== CONTINUING REPORT DEBUG ===');
    \Log::info('Total scholars for continuing: ' . $scholars->count());
    \Log::info('Category A count: ' . $scholars->where('category', 'a')->count());  // ✅ Changed
    \Log::info('Category B count: ' . $scholars->where('category', 'b')->count());  // ✅ Changed
    
    // Log first scholar to see structure
    $firstScholar = $scholars->first();
    if ($firstScholar) {
        \Log::info('First scholar data: ' . json_encode($firstScholar));
    }
    
    // Table A: Continuing Scholars (starts at row 2)
    $currentRow = 7;
    $currentRow = $this->populateContinuingTableA($sheet, $scholars->where('category', 'a'), $hiddenColumns, $currentRow);  // ✅ Changed
    
    // Table B: Add header and populate
    $currentRow += 2; // Add spacing
    
    // Define Table B columns
    $tableBColumns = [
        'cont_b_no' => 'No.',
        'cont_b_name' => 'Name',
        'cont_b_application_number' => 'Application Number',
        'cont_b_scholarship_type' => 'Scholarship Type',
        'cont_b_degree_program' => 'Degree Program',
        'cont_b_academic_year' => 'Academic Year of Graduation',
        'cont_b_term_graduation' => 'Term of Graduation',
        'cont_b_remarks' => 'Remarks'
    ];
    
    // Populate Table B data
    $currentRow = $this->populateContinuingTableB($sheet, $scholars->where('category', 'b'), $hiddenColumns, $currentRow);  // ✅ Changed
}

private function populateContinuingTableA($sheet, $scholars, $hiddenColumns, $startRow)
{
    \Log::info('Continuing Table A - Starting at row: ' . $startRow . ' with ' . $scholars->count() . ' scholars');
    
    $row = $startRow;
    $no = 1;
    $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M']; // ✅ Added 'M' (13 columns)

    foreach ($scholars as $scholar) {
        $colIndex = 0;
        
        // Column A: No.
        if (!in_array('cont_a_no', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $no);
        }
        $colIndex++;
        
        // Column B: Name
        if (!in_array('cont_a_name', $hiddenColumns)) {
            $fullName = trim(($scholar->first_name ?? '') . ' ' . 
                            ($scholar->middle_name ?? '') . ' ' . 
                            ($scholar->last_name ?? '') . ' ' . 
                            ($scholar->suffix ?? ''));
            $sheet->setCellValue($columns[$colIndex] . $row, $fullName);
        }
        $colIndex++;
        
        // Column C: Application Number
        if (!in_array('cont_a_application_number', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->application_no ?? '');
        }
        $colIndex++;
        
        // Column D: Scholarship Type
        if (!in_array('cont_a_scholarship_type', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->scholarship_type ?? '');
        }
        $colIndex++;
        
        // Column E: Degree Program
        if (!in_array('cont_a_degree_program', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->degree_program ?? '');
        }
        $colIndex++;
        
        // Column F: Year of Approval
        if (!in_array('cont_a_year_approval', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->year_of_approval ?? '');
        }
        $colIndex++;
        
        // Column G: Last Term of Enrollment
        if (!in_array('cont_a_last_term', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->last_term_enrollment ?? '');
        }
        $colIndex++;
        
        // Column H: Good Academic Standing
        if (!in_array('cont_a_good_standing', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, 
                isset($scholar->good_academic_standing) ? ($scholar->good_academic_standing ? 'Yes' : 'No') : ''
            );
        }
        $colIndex++;
        
        // Column I: Standing Explanation
        if (!in_array('cont_a_standing_explanation', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->standing_explanation ?? '');
        }
        $colIndex++;
        
        // Column J: Finish on Time
        if (!in_array('cont_a_finish_on_time', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, 
                isset($scholar->finish_on_time) ? ($scholar->finish_on_time ? 'Yes' : 'No') : ''
            );
        }
        $colIndex++;
        
        // Column K: Finish Explanation
        if (!in_array('cont_a_finish_explanation', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->finish_explanation ?? '');
        }
        $colIndex++;
        
        // Column L: Recommendation
        if (!in_array('cont_a_recommendation', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->recommendation ?? '');
        }
        $colIndex++;
        
        // Column M: Rationale
        if (!in_array('cont_a_rationale', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->rationale ?? '');
        }

        $row++;
        $no++;
    }
    
    \Log::info('Continuing Table A - Ended at row: ' . $row);
    return $row;
}

private function populateContinuingTableB($sheet, $scholars, $hiddenColumns, $startRow)
{
    \Log::info('Continuing Table B - Starting at row: ' . $startRow . ' with ' . $scholars->count() . ' scholars');
    
    $row = $startRow;
    $no = 1;
    $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']; // 8 columns

    foreach ($scholars as $scholar) {
        $colIndex = 0;
        
        if (!in_array('cont_b_no', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $no);
        }
        $colIndex++;
        
        if (!in_array('cont_b_name', $hiddenColumns)) {
            $fullName = trim(($scholar->first_name ?? '') . ' ' . 
                            ($scholar->middle_name ?? '') . ' ' . 
                            ($scholar->last_name ?? '') . ' ' . 
                            ($scholar->suffix ?? ''));
            $sheet->setCellValue($columns[$colIndex] . $row, $fullName);
        }
        $colIndex++;
        
        if (!in_array('cont_b_application_number', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->application_no ?? '');
        }
        $colIndex++;
        
        if (!in_array('cont_b_scholarship_type', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->scholarship_type ?? '');
        }
        $colIndex++;
        
        if (!in_array('cont_b_degree_program', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->degree_program ?? '');
        }
        $colIndex++;
        
        if (!in_array('cont_b_academic_year', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->academic_year_graduation ?? '');
        }
        $colIndex++;
        
        if (!in_array('cont_b_term_graduation', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->term_of_graduation ?? '');
        }
        $colIndex++;
        
        if (!in_array('cont_b_remarks', $hiddenColumns)) {
            $sheet->setCellValue($columns[$colIndex] . $row, $scholar->remarks ?? '');
        }

        $row++;
        $no++;
    }
    
    \Log::info('Continuing Table B - Ended at row: ' . $row);
    return $row;
}

public function printPersonalInformation(Request $request)
{
    $semester = $request->input('semester');
    $academicYear = $request->input('academic_year');
    
    // Build query with filters
    $query = DB::table('ched_info_table')
        ->where('status', 'approved');
    
    if ($semester) {
        $query->where('school_term', $semester);
    }
    
    if ($academicYear) {
        $query->where('academic_year', $academicYear);
    }
    
    $scholars = $query->select(
        'id',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'street',
        'barangay',
        'city',
        'province',
        'zip_code',
        'district',
        'region',
        'email',
        'date_of_birth',
        'contact_no',
        'sex',
        'age'
    )
    ->orderBy('last_name')
    ->get();
    
    return view('admin.reports.ched-personalinfo-print', compact('scholars'));
}

}