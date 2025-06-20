<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id', 'report_name', 'report_content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

