<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RemarkNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // store in DB (for bell) and optionally email
        return ['database']; 
        // if you also want to email: return ['database', 'mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * This is what goes into the `notifications` table as JSON.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Admin left a remark: " . ($this->data['remarks'] ?? 'Check your application'),
            'url'     => route('applicant.my-application'), // ✅ adjust route name if different
        ];
    }
    
}
