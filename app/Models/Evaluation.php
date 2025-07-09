<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'evaluation_id';

    protected $fillable = [
        'application_form_id',
        'evaluator_id',
        'remarks',
        'program_study',
        'specialization',
        'scholarship_program',
        'graduation_year',
        'course_school',
        'admission_program',
        'admission_school',
        'admission_date',
        'academic_gpa',
        'academic_honors',
        'employer_deped',
        'years_service',
        'reg_deped',
        'part_deped',
        'performance_rating',
        'physically_fit',
        'health_comments',
        'age',
        'endorsement_1',
        'endorsement_2',
        'grad_units',
        'gpa_lateral',
        'decision',
        'evaluator_name',
        'evaluation_date',
    ];

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id', 'application_form_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id', 'user_id');
    }
}
