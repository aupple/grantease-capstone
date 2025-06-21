<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemarkAttachment extends Model
{
    protected $table = 'remark_attachments';

    protected $fillable = ['remark_id', 'attachment_id'];

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = null;
}
