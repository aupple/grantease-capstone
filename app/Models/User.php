<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 👇 Custom primary key
    protected $primaryKey = 'user_id';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // 👈 must be included for seeding/registration
    ];

    /**
     * Hidden attributes for arrays
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts for model properties
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
