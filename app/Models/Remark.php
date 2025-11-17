<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    protected $primaryKey = 'remark_id';
    
    protected $fillable = [
        'application_form_id',
        'document_name',
        'remark_text',
    ];

    public function application()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id', 'application_form_id');
    }

    public function attachments()
    {
        return $this->hasMany(RemarkAttachment::class, 'remark_id', 'remark_id');
    }
}