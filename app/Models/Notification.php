<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_id', 'message', 'is_read', 'sent_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function remarks()
    {
        return $this->belongsToMany(Remark::class, 'remark_notifications', 'notification_id', 'remark_id');
    }
}

