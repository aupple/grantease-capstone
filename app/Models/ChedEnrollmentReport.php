<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChedEnrollmentReport extends Model
{
    use HasFactory;

    protected $table = 'ched_enrollment_reports';

    protected $fillable = [
        'ched_info_id',
        'degree_program',
        'enrollment_status',
        'units_enrolled',
        'retaken_subjects',
        'remarks',
        'issue_status',
        'non_enrollment_status',
        'termination_status',
        'others_status',
        'status_description',
        'category',
    ];

    // Relationship to ChedInfo
    public function chedInfo()
    {
        return $this->belongsTo(ChedInfo::class, 'ched_info_id');
    }
}