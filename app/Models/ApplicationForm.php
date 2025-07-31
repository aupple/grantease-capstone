<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_form_id'; // custom PK
    protected $fillable = [
    'user_id', 'status', 'submitted_at',
    'program', 'academic_year', 'school_term', 'application_no',

    // Personal Info
    'last_name', 'first_name', 'middle_name', 'suffix',
    'email', 'contact', 'dob', 'gender', 'citizenship',
    'birthplace', 'home_address', 'current_address',
    'civil_status', 'birthdate', 'age', 'sex', // optional overlaps

    'father_name', 'father_occupation', 'mother_name', 'mother_occupation',
    'permanent_address', 'zip_code', 'region', 'district', 'passport_no',
    'phone_number',

    // Academic Info
    'school', 'year_level', 'reason',
    'bs_school', 'bs_course', 'bs_grad_year', 'bs_honors', 'bs_scholarship_type',
    'ms_school', 'ms_course', 'ms_grad_year', 'ms_honors', 'ms_scholarship_type',
    'phd_school', 'phd_course', 'phd_grad_year', 'phd_honors', 'phd_scholarship_type',

    'graduate_strand', 'strand_category', 'strand_type',
    'application_type', 'scholarship_type', 'entry_status',
    'new_university', 'new_course', 'lateral_university', 'lateral_course',
    'units_earned', 'units_remaining', 'research_approved',
    'research_title', 'last_thesis_date',

    // Employment Info
    'employed', 'employer', 'position', 'employment_status', 'length_of_service',
    'company_name', 'company_address', 'company_email', 'company_website', 'company_phone', 'company_fax',
    'business_name', 'business_address', 'business_email', 'business_type', 'years_operation',

    // Studying / Government
    'studying', 'school_name', 'school_course', 'school_type',
    'government_service', 'gs_details',

    // Research & Awards
    'research_plans', 'career_plans', 'research_scope', 'research_timeline',
    'rnd_involvement', 'publications', 'awards',

    // File uploads
    'passport_picture', 'form137', 'cert_employment', 'cert_purpose',

    // Misc
    'agree_terms', 'remarks'
];


    // 🔁 Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    // 🔁 Relationship with Evaluation
public function evaluation()
{
    return $this->hasOne(Evaluation::class, 'application_form_id', 'application_form_id');
}

}
