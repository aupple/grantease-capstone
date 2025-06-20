<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appeal extends Model
{
    use HasFactory;

    protected $primaryKey = 'appeal_id';

    protected $fillable = [
        'application_id', 'user_id', 'appeal_reason',
        'status', 'date_filed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function application()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_id');
    }
}
