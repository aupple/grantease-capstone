<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarMonitoring extends Model
{
    use HasFactory;


    protected $table = 'scholar_monitorings';

    protected $fillable = [
        'scholar_id',
        'course',
        'school',
        'enrollment_type',
        'scholarship_duration',
        'date_started',
        'expected_completion',
        'remarks',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class, 'scholar_id', 'id');
    }
}
