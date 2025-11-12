<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_form_id';

    // Allow mass assignment for all fields
    protected $fillable = [
        'user_id',

        // Step 1: Basic Info
        'academic_year',
        'school_term',
        'application_no',
        'passport_picture',


        // Step 2: Personal Information
        'last_name',
        'first_name',
        'middle_name',
        'address_no',
        'address_street',
        'barangay',
        'city',
        'province',
        'zip_code',
        'region',
        'district',
        'passport_no',
        'email_address',
        'current_mailing_address',
        'telephone_nos',
        'civil_status',
        'date_of_birth',
        'age',
        'sex',
        'father_name',
        'mother_name',

        // Step 3: Educational Background
        'bs_degree',
        'bs_period',
        'bs_field',
        'bs_university',
        'bs_scholarship_type',
        'bs_scholarship_others',
        'bs_remarks',
        'intended_degree',

        'ms_degree',
        'ms_period',
        'ms_field',
        'ms_university',
        'ms_scholarship_type',
        'ms_scholarship_others',
        'ms_remarks',

        'phd_degree',
        'phd_period',
        'phd_field',
        'phd_university',
        'phd_scholarship_type',
        'phd_scholarship_others',
        'phd_remarks',

        // Step 4: Graduate Scholarship Intentions
        'strand_category',
        'applicant_type',
        'scholarship_type',
        'new_applicant_university',
        'new_applicant_course',
        'lateral_university_enrolled',
        'lateral_course_degree',
        'lateral_units_earned',
        'lateral_remaining_units',
        'research_topic_approved',
        'research_title',
        'last_enrollment_date',

        // Step 5: Employment
        'employment_status',

        // Employed fields
        'employed_position',
        'employed_length_of_service',
        'employed_company_name',
        'employed_company_address',
        'employed_email',
        'employed_website',
        'employed_telephone',
        'employed_fax',

        // Self-employed fields
        'self_employed_business_name',
        'self_employed_address',
        'self_employed_email_website',
        'self_employed_telephone',
        'self_employed_fax',
        'self_employed_type_of_business',
        'self_employed_years_of_operation',

        // Step 5: Research & Career Plans
        'research_plans',
        'career_plans',

        // Step 6: Research, Publications, Awards
        'rnd_involvement',
        'publications',
        'awards',
        'thesis_title',
        'units_required',
        'duration',

        // Step 7: Upload Documents
        'birth_certificate_pdf',
        'transcript_of_record_pdf',
        'endorsement_1_pdf',
        'endorsement_2_pdf',
        'recommendation_head_agency_pdf',
        'form_2a_pdf',
        'form_2b_pdf',
        'form_a_research_plans_pdf',
        'form_b_career_plans_pdf',
        'form_c_health_status_pdf',
        'nbi_clearance_pdf',
        'letter_of_admission_pdf',
        'approved_program_of_study_pdf',
        'lateral_certification_pdf',
        'form137',
        'cert_employment',
        'cert_purpose',
        'reason',
        'program',


        // Step 8: Declaration / Terms
        'terms_and_conditions_agreed',
        'applicant_signature',
        'declaration_date',

        // Status & tracking
        'status',
        'submitted_at',
        'remarks',
        'remark',
    ];

    // Cast JSON fields and booleans properly
    protected $casts = [
        'bs_scholarship_type' => 'array',
        'ms_scholarship_type' => 'array',
        'phd_scholarship_type' => 'array',
        'scholarship_type' => 'array',
        'rnd_involvement' => 'array',
        'scholarship_duration' => 'array',
        'publications' => 'array',
        'awards' => 'array',
        'research_topic_approved' => 'boolean',
        'terms_and_conditions_agreed' => 'boolean',
        'lateral_units_earned' => 'integer',
        'lateral_remaining_units' => 'integer',
    ];

    // Optional: define relationship to user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    public function scholar()
    {
        return $this->hasOne(Scholar::class, 'application_form_id', 'application_form_id');
    }

    public function documentRemarks()
    {
        return $this->hasMany(Remark::class, 'evaluation_id', 'application_form_id');
    }
    
    public function remarks()
    {
        return $this->documentRemarks();
    }

    public function scholar()
    {
        return $this->hasOne(Scholar::class, 'application_form_id', 'application_form_id');
    }
    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'application_form_id', 'application_form_id');
    }
}
