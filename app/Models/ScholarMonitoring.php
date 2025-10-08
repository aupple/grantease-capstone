<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'last_name',
        'first_name',
        'middle_name',
        'level',
        'course',
        'school',
        'new_or_lateral',
        'enrollment_type',
        'scholarship_duration',
        'date_started',
        'expected_completion',
        'year_awarded',
        'degree_type',
        'status_code',
        'total',
        'status',
        'remarks',
    ];

    public function scholar()
    {
         return $this->belongsTo(Scholar::class, 'scholar_id', 'id');
    }
}
