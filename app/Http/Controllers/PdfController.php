<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function healthCertificate()
    {
        $pdf = Pdf::loadView('pdf.health-certificate'); // make sure this view exists
        return $pdf->download('health-certificate.pdf');
    }

    public function scoreSheet()
    {
        $pdf = Pdf::loadView('pdf.score-sheet');
        return $pdf->download('score-sheet.pdf');
    }

    public function recommendationForm()
    {
        $pdf = Pdf::loadView('pdf.recommendation-form');
        return $pdf->download('recommendation-form.pdf');
    }

    public function researchPlans()
    {
        $pdf = Pdf::loadView('pdf.research-plans');
        return $pdf->download('research-plans.pdf');
    }

    public function careerPlans()
    {
        $pdf = Pdf::loadView('pdf.career-plans');
        return $pdf->download('career-plans.pdf');
    }

    public function certificationEmployment()
    {
        $pdf = Pdf::loadView('pdf.certification-employment');
        return $pdf->download('certification-employment.pdf');
    }

    public function certificationDepEd()
    {
        $pdf = Pdf::loadView('pdf.certification-deped');
        return $pdf->download('certification-deped.pdf');
    }

    public function certificationHealthStatus()
    {
        $pdf = Pdf::loadView('pdf.certification-health-status');
        return $pdf->download('certification-health-status.pdf');
    }
}
