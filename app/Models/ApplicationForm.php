<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_form_id'; // custom PK
    protected $fillable = [
    'user_id',
    'program',
    'school',
    'year_level',
    'reason',
    'status',
    'remarks',
    'submitted_at',

    // 📌 Personal Info
    'permanent_address', 'zip_code', 'region', 'district', 'passport_no',
    'current_address', 'civil_status', 'birthdate', 'age', 'sex',
    'father_name', 'mother_name', 'phone_number',

    // 📌 Academic Info
    'bs_field', 'bs_school', 'bs_scholarship', 'bs_remarks',
    'ms_field', 'ms_school', 'ms_scholarship', 'ms_remarks',
    'phd_field', 'phd_school', 'phd_scholarship', 'phd_remarks',
    'strand_category', 'strand_type', 'scholarship_type',
    'new_university', 'new_course', 'lateral_university', 'lateral_course',
    'units_earned', 'units_remaining', 'research_approved',
    'research_title', 'last_thesis_date',

    // 📌 Employment & R&D
    'employment_status', 'position', 'length_of_service',
    'company_name', 'company_address', 'company_email',
    'company_website', 'company_phone', 'company_fax',
    'business_name', 'business_address', 'business_email',
    'business_type', 'years_operation',
    'research_plans', 'career_plans',
    'rnd_involvement', 'publications', 'awards',
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
