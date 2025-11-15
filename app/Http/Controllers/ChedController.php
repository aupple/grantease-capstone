<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ApplicationForm;
use App\Models\Evaluation;
use App\Models\Enrollment;
use App\Models\ChedInfo;
use Illuminate\Support\Facades\Storage;

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
            $sheet->setCellValue('C'.$row, $applicant->application_number);
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
}