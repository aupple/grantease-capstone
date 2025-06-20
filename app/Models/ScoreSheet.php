<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScoreSheet extends Model
{
    use HasFactory;

    protected $primaryKey = 'score_sheet_id';

    protected $fillable = [
        'evaluation_id', 'criteria_name', 'score',
        'date_of_interview', 'status'
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }
}

