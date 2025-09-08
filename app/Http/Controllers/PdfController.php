<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ApplicationForm;
use App\Models\Evaluation;

class PdfController extends Controller
{
 
   public function scoreSheet()
    {
        $userId = auth()->user()->user_id;
        $applicant = ApplicationForm::with('user')->where('user_id', $userId)->firstOrFail();
        $evaluation = Evaluation::where('application_form_id', $applicant->application_form_id)->first();

        $pdf = Pdf::loadView('applicant.pdf.score_sheet', compact('applicant', 'evaluation'));
        return $pdf->download('Score_Sheet.pdf');
    }

    public function recommendationForm()
    {
        $userId = auth()->user()->user_id;
        $applicant = ApplicationForm::with('user')->where('user_id', $userId)->firstOrFail();

        $pdf = Pdf::loadView('applicant.pdf.recommendation_form', compact('applicant'));
        return $pdf->download('Recommendation_Form.pdf');
    }

    public function researchPlans()
    {
        $userId = auth()->user()->user_id;
        $applicant = ApplicationForm::with('user')->where('user_id', $userId)->firstOrFail();

        $pdf = Pdf::loadView('applicant.pdf.research_plans', compact('applicant'));
        return $pdf->download('Research_Plans.pdf');
    }

    public function careerPlans()
    {
        $userId = auth()->user()->user_id;
        $applicant = ApplicationForm::with('user')->where('user_id', $userId)->firstOrFail();

        $pdf = Pdf::loadView('applicant.pdf.career_plans', compact('applicant'));
        return $pdf->download('Career_Plans.pdf');
    }

    public function certificationEmployment()
    {
        $userId = auth()->user()->user_id;
        $applicant = ApplicationForm::with('user')->where('user_id', $userId)->firstOrFail();

        $pdf = Pdf::loadView('applicant.pdf.certification_employment', compact('applicant'));
        return $pdf->download('Certification_of_Employment.pdf');
    }

    public function certificationDepEd()
    {
        $userId = auth()->user()->user_id;
        $applicant = ApplicationForm::with('user')->where('user_id', $userId)->firstOrFail();

        $pdf = Pdf::loadView('applicant.pdf.certification_deped', compact('applicant'));
        return $pdf->download('Certification_of_DepEd_Employment.pdf');
    }

    public function certificationHealthStatus()
    {
        $userId = auth()->user()->user_id;
        $applicant = ApplicationForm::with('user')->where('user_id', $userId)->firstOrFail();

        $pdf = Pdf::loadView('applicant.pdf.certification_health_status', compact('applicant'));
        return $pdf->download('Certification_of_Health_Status.pdf');
    }
}
