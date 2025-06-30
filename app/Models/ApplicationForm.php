<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_form_id'; // custom PK
    protected $fillable = ['user_id', 'program', 'school', 'year_level', 'reason', 'status', 'remarks', 'submitted_at'];

    // 🔁 Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
