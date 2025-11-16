<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class PdfController extends Controller
{
    /**
     * Serve blank PDF templates with user's name in filename
     */
    private function servePdfTemplate($templateName, $downloadName)
    {
        $user = auth()->user();
        $path = storage_path("app/pdf-templates/{$templateName}");
        
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Form template not found. Please contact administrator.');
        }
        
        // Add user's name to filename for organization
        $filename = str_replace(
            [' ', '.pdf'],
            ['_', ''],
            $downloadName
        ) . '_' . $user->last_name . '_' . $user->first_name . '.pdf';
        
        return response()->download($path, $filename);
    }
    
    /**
     * Generate PDF from Blade template with user data
     */
    private function generatePdfFromBlade($bladePath, $downloadName, $additionalData = [])
    {
        $user = auth()->user();
        
        // Get the latest application form for this user
        $application = $user->applicationForms()->latest()->first();
        
        // If no application exists, create a blank one with basic user info
        if (!$application) {
            // Create a temporary object with user data for the PDF
            $application = (object) [
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'email_address' => $user->email,
                'sex' => null,
                'date_of_birth' => null,
                'address_street' => null,
                'barangay' => null,
                'city' => null,
                'province' => null,
                'zip_code' => null,
                'region' => null,
                'intended_degree' => null,
                'new_applicant_university' => null,
                'lateral_university_enrolled' => null,
                'research_title' => null,
                'research_plans' => null,
                'career_plans' => null,
                'thesis_title' => null,
                'duration' => null,
                'employment_status' => null,
                'employed_company_name' => null,
                'employed_position' => null,
                'employed_length_of_service' => null,
                'employed_company_address' => null,
            ];
        }
        
        // Merge user data, application data, and additional data
        $data = array_merge([
            'user' => $user,
            'application' => $application
        ], $additionalData);
        
        // Generate filename
        $filename = str_replace(
            [' ', '.pdf'],
            ['_', ''],
            $downloadName
        ) . '_' . $user->last_name . '_' . $user->first_name . '.pdf';
        
        try {
            // Generate PDF from Blade view
            $pdf = DomPDF::loadView($bladePath, $data);
            
            // Set paper size and orientation
            $pdf->setPaper('letter', 'portrait');
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }
    
    public function scoreSheet()
    {
        $user = auth()->user();
        $pdf = DomPDF::loadView('applicant.pdf.score_sheet', compact('user'));
        return $pdf->download('DOST_Score_Sheet.pdf');
    }

    public function recommendationForm()
    {
        $user = auth()->user();
        $application = $user->applicationForms()->latest()->first();
        
        // Get applicant data
        $lastName = $application->last_name ?? $user->last_name ?? '';
        $firstName = $application->first_name ?? $user->first_name ?? '';
        $middleName = $application->middle_name ?? $user->middle_name ?? '';
        $degreeApplied = $application->intended_degree ?? '';
        
        return $this->fillRecommendationPdf($lastName, $firstName, $middleName, $degreeApplied);
    }
    
    /**
     * Fill the recommendation form PDF with applicant information
     */
    private function fillRecommendationPdf($lastName, $firstName, $middleName, $degreeApplied)
    {
        $user = auth()->user();
        $templatePath = storage_path('app/pdf-templates/recommendation-form-blank.pdf');
        
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Recommendation form template not found.');
        }
        
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            
            // Get page count
            $pageCount = $pdf->setSourceFile($templatePath);
            
            // Import first page
            $templateId = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
            
            // Set font
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(0, 0, 0);
            
            // TODO: Adjust these X and Y coordinates based on your actual PDF
            // Use the coordinate finder tool to get exact positions
            
            // Last Name field (adjust X, Y as needed)
            $pdf->SetXY(35, 53);
            $pdf->Write(0, $lastName);
            
            // Given Name field
            $pdf->SetXY(35, 59.5);    
            $pdf->Write(0, $firstName);
            
            // Middle Name field
            $pdf->SetXY(35, 65.5);
            $pdf->Write(0, $middleName);
            
            
            // Import remaining pages without modification
            for ($pageNo = 2; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($templateId);
            }
            
            // Generate filename
            $filename = 'DOST_Recommendation_Form_' . $user->last_name . '_' . $user->first_name . '.pdf';
            
            // Output PDF
            return response()->make($pdf->Output('S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    public function researchPlans()
    {
        return $this->generatePdfFromBlade(
            'applicant.pdf.research_plans',
            'Form_A_Research_Plans'
        );
    }

    public function careerPlans()
    {
        return $this->generatePdfFromBlade(
            'applicant.pdf.career_plans',
            'Form_B_Career_Plans'
        );
    }

    public function certificationEmployment()
    {
        return $this->generatePdfFromBlade(
            'applicant.pdf.certification_employment',
            'Form_2A_Certification_Employment'
        );
    }

    public function certificationDepEd()
    {
        return $this->generatePdfFromBlade(
            'applicant.pdf.certification_deped',
            'Form_2B_Certification_DepEd'
        );
    }

    public function certificationHealthStatus()
    {
        return $this->generatePdfFromBlade(
            'applicant.pdf.certification_health_status',
            'Form_C_Certification_Health_Status'
        );
    }
}