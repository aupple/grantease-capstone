<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemarkNotification extends Model
{
    protected $table = 'remark_notifications';

    protected $fillable = ['remark_id', 'notification_id'];

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = null;
}
