<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $status;
    public $remarks;

    /**
     * Create a new message instance.
     */
    public function __construct($status, $remarks = null)
    {
        $this->status = $status;
        $this->remarks = $remarks;
    }

    /**
     * Build the message.
     */
    public function build()
{
    return $this->subject('Your Scholarship Application Status')
                ->view('emails.application-status')
                ->with([
                    'status' => $this->status,
                    'remarks' => $this->remarks,
                ]);
}

}
