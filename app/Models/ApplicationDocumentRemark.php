<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDocumentRemark extends Model
{
    protected $fillable = [
        'application_form_id',
        'document_name',
        'remark',
    ];
}