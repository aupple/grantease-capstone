<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'evaluation_id';

    protected $fillable = [
        'application_id', 'user_id', 'evaluation_date',
        'comments', 'status', 'criteria', 'indicators'
    ];

    public function application()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scoreSheets()
    {
        return $this->hasMany(ScoreSheet::class, 'evaluation_id');
    }

    public function remarks()
    {
        return $this->hasMany(Remark::class, 'evaluation_id');
    }
}
