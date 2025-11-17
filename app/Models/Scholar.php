<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_form_id',
        'status',
        'start_date',
        'end_date',
        'verified_documents',
    ];

    // ✅ ADD THIS - This tells Laravel to automatically convert JSON to array
    protected $casts = [
        'verified_documents' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id', 'application_form_id');
    }

    public function monitorings()
    {
        return $this->hasMany(ScholarMonitoring::class, 'scholar_id', 'id');
    }
}