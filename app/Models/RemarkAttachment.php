<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemarkAttachment extends Model
{
    protected $primaryKey = 'remark_attachment_id';
    
    protected $fillable = [
        'remark_id',
        'file_path',
        'file_type',
    ];

    public function remark()
    {
        return $this->belongsTo(Remark::class, 'remark_id', 'remark_id');
    }
}