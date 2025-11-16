<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChedGradeReport extends Model
{
    protected $fillable = [
        'ched_info_id',
        'degree_program',
        'enrolled_subjects',
        'subjects_passed',
        'incomplete_grades',
        'subjects_failed',
        'no_grades',
        'not_credited_subjects',
        'status',
        'gpa',
        'remarks',
    ];

    public function chedInfo()
    {
        return $this->belongsTo(ChedInfo::class, 'ched_info_id');
    }
}