<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remark extends Model
{
    use HasFactory;

    protected $primaryKey = 'remark_id';

    protected $fillable = [
        'evaluation_id', 'application_id', 'user_id',
        'remark_text', 'remark_note', 'created_by'
    ];

    public function application()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments()
    {
        return $this->belongsToMany(Attachment::class, 'remark_attachments', 'remark_id', 'attachment_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'remark_notifications', 'remark_id', 'notification_id');
    }
}

