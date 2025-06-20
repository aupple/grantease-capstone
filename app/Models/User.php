<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username', 'password', 'email', 'first_name',
        'last_name', 'contact_number', 'application_no'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    public function applications()
    {
        return $this->hasMany(ApplicationForm::class, 'user_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'user_id');
    }

    public function remarks()
    {
        return $this->hasMany(Remark::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function updatedSettings()
    {
        return $this->hasMany(SystemSetting::class, 'updated_by_user_id');
    }
}

