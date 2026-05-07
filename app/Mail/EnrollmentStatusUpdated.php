<?php

namespace App\Mail;

use App\Models\EnrollmentApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $title;
    public $body;

    public function __construct(EnrollmentApplication $application, string $title, string $body)
    {
        $this->application = $application;
        $this->title = $title;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject($this->title . ' - ' . $this->application->application_number)
            ->view('emails.enrollment.status-updated');
    }
}
