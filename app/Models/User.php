<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'program_type',
        'role_id',
        'personal_info_completed', // ← Add this
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'personal_info_completed' => 'boolean', // ← Add this
    ];

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    // Relationship to Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    // Relationship: User has many ApplicationForms
    public function applicationForms()
    {
        return $this->hasMany(ApplicationForm::class, 'user_id', 'user_id');
    }

    // Relationship: User has one CHED Personal Info
    public function chedInfo()
    {
        return $this->hasOne(ChedInfo::class, 'user_id', 'user_id');
    }
}