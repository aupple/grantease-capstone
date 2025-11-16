<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChedContinuingReport extends Model
{
    use HasFactory;

    protected $table = 'ched_continuing_reports';

    protected $fillable = [
        'ched_info_id',
        'scholarship_type',
        'degree_program',
        'year_of_approval',
        'last_term_enrollment',
        'good_academic_standing',
        'standing_explanation',
        'finish_on_time',
        'finish_explanation',
        'recommendation',
        'rationale',
        'academic_year_graduation',
        'term_of_graduation',
        'remarks',
        'category',
    ];

    protected $casts = [
        'good_academic_standing' => 'boolean',
        'finish_on_time' => 'boolean',
    ];

    public function chedInfo()
    {
        return $this->belongsTo(ChedInfo::class, 'ched_info_id');
    }
}