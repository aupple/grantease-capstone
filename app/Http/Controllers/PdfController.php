<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Correct PDF facade

class PdfController extends Controller
{
    public function healthCertificate()
    {
        return $this->generatePdf('applicant.pdf.health_certificate', 'health_certificate.pdf');
    }

    public function scoreSheet()
    {
        return $this->generatePdf('applicant.pdf.score_sheet', 'score_sheet.pdf');
    }

    public function recommendationForm()
    {
        return $this->generatePdf('applicant.pdf.recommendation_form', 'recommendation_form.pdf');
    }

    public function researchPlans()
    {
        return $this->generatePdf('applicant.pdf.research_plans', 'research_plans.pdf');
    }

    public function careerPlans()
    {
        return $this->generatePdf('applicant.pdf.career_plans', 'career_plans.pdf');
    }

    public function certificationEmployment()
    {
        return $this->generatePdf('applicant.pdf.certification_employment', 'certification_employment.pdf');
    }

    public function certificationDepEd()
    {
        return $this->generatePdf('applicant.pdf.certification_deped', 'certification_deped.pdf');
    }

    public function certificationHealthStatus()
    {
        return $this->generatePdf('applicant.pdf.certification_health_status', 'certification_health_status.pdf');
    }

    /**
     * Helper method to generate a PDF.
     */
    private function generatePdf(string $view, string $filename)
    {
        $pdf = Pdf::loadView($view);
        return $pdf->download($filename);
    }
}
