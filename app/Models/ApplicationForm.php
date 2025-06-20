<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_id';

    protected $fillable = [
        'user_id', 'scholarship_type', 'status',
        'date_submitted', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'application_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'application_id');
    }

    public function remarks()
    {
        return $this->hasMany(Remark::class, 'application_id');
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'application_id');
    }
}
