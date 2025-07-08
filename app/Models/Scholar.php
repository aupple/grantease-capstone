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
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id');
    }
}
