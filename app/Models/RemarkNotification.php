<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemarkNotification extends Model
{
    protected $primaryKey = 'notification_id';
    protected $fillable = ['remark_id','user_id','message','is_read'];
}
