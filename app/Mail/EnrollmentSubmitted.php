<?php

namespace App\Mail;

use App\Models\EnrollmentApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(EnrollmentApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Enrollment Application Submitted - ' . $this->application->application_number)
            ->view('emails.enrollment.submitted');
    }
}
