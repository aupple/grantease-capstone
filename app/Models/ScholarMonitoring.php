<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_awarded',
        'degree_type',
        'status_code',
        'total',
    ];

    public $timestamps = false;
}

