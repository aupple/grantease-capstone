<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    protected $primaryKey = 'remark_id';
    protected $fillable = ['evaluation_id', 'content', 'admin_id']; // add admin_id if you track who sent it

    public function attachments()
    {
        return $this->hasMany(RemarkAttachment::class, 'remark_id', 'remark_id');
    }

    public function notifications()
    {
        return $this->hasMany(RemarkNotification::class, 'remark_id', 'remark_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
