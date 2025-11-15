<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChedPersonalInformation extends Model
{
    use HasFactory;

    protected $table = 'ched_info_table';

    protected $fillable = [
        'user_id',
        'academic_year',
        'school_term',
        'application_no',
        'passport_photo',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'province',
        'city',
        'barangay',
        'street',
        'house_no',
        'zip_code',
        'region',
        'district',
        'passport_no',
        'email',
        'mailing_address',
        'contact_no',
        'civil_status',
        'date_of_birth',
        'age',
        'sex',
        'father_name',
        'mother_name',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'age' => 'integer',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}");
    }
}