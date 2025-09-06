<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_form_id';

    protected $fillable = [
        'user_id',

        // Basic info
        'program', 'school', 'year_level', 'reason',

        // Status & tracking
        'status', 'submitted_at', 'remarks',

        // Personal info
        'last_name', 'first_name', 'middle_name', 'suffix',
        'email', 'contact', 'dob', 'gender', 'citizenship',
        'birthplace', 'home_address', 'current_address',
        'civil_status', 'birthdate', 'age', 'sex',
        'father_name', 'father_occupation',
        'mother_name', 'mother_occupation',
        'permanent_address', 'zip_code', 'region', 'district',
        'passport_no', 'phone_number',

        // Academic
        'academic_year', 'school_term', 'application_no',

        // BS
        'bs_field', 'bs_university', 'bs_scholarship_type',
        'bs_scholarship_others', 'bs_remarks',
        'bs_school', 'bs_course', 'bs_grad_year', 'bs_honors',

        // MS
        'ms_field', 'ms_school', 'ms_course', 'ms_grad_year', 'ms_honors',
        'ms_scholarship', 'ms_scholarship_type', 'ms_remarks',

        // PhD
        'phd_field', 'phd_school', 'phd_course', 'phd_grad_year', 'phd_honors',
        'phd_scholarship', 'phd_scholarship_type', 'phd_remarks',

        // Strand
        'graduate_strand', 'strand_category', 'strand_type',

        // Application info
        'application_type', 'scholarship_type', 'entry_status',
        'new_university', 'new_course',
        'lateral_university', 'lateral_course',
        'units_earned', 'units_remaining',
        'research_approved', 'research_title', 'last_thesis_date',

        // Employment
        'employed', 'employer', 'position', 'employment_status', 'length_of_service',
        'company_name', 'company_address', 'company_email', 'company_website',
        'company_phone', 'company_fax',
        'business_name', 'business_address', 'business_email', 'business_type', 'years_operation',
        'studying', 'school_name', 'school_course', 'school_type',
        'government_service', 'gs_details',

        // Research, career
        'research_plans', 'career_plans', 'research_scope', 'research_timeline',
        'rnd_involvement', 'publications', 'awards',

        // File uploads
        'passport_picture', 'form137', 'cert_employment', 'cert_purpose',
        'birth_certificate_pdf', 'transcript_of_record_pdf',
        'endorsement_1_pdf', 'endorsement_2_pdf',
        'recommendation_head_agency_pdf',
        'form_2a_pdf', 'form_2b_pdf',
        'form_a_research_plans_pdf', 'form_b_career_plans_pdf',
        'form_c_health_status_pdf',
        'nbi_clearance_pdf', 'letter_of_admission_pdf',
        'approved_program_of_study_pdf', 'lateral_certification_pdf',

        // Declaration
        'terms_and_conditions_agreed', 'applicant_signature', 'declaration_date',
    ];

    protected $casts = [
        'terms_and_conditions_agreed' => 'boolean',
        'birthdate' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class, 'application_form_id', 'application_form_id');
    }
}
