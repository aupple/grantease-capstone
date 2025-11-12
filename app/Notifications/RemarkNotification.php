<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class RemarkNotification extends Notification
{
    use Queueable;

    protected $remark;

    /**
     * Create a new notification instance.
     */
    public function __construct($remark)
    {
        $this->remark = $remark;
    }

    /**
     * Define the notification delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // Store in database only (no email)
    }

    /**
     * Store data for database notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'New remark from admin: ' . Str::limit($this->remark->content, 100),
            'url' => route('notifications.index'), // redirect to notifications page
        ];
    }
}
