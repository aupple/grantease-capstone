<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    protected $primaryKey = 'attachment_id';

    protected $fillable = [
        'application_id', 'file_name', 'file_path',
        'uploaded_at', 'file_type'
    ];

    public function application()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_id');
    }

    public function remarks()
    {
        return $this->belongsToMany(Remark::class, 'remark_attachments', 'attachment_id', 'remark_id');
    }
}

