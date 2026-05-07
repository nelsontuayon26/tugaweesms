<?php

namespace App\Mail;

use App\Models\EnrollmentApplication;
use App\Models\User;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $user;
    public $student;

    public function __construct(EnrollmentApplication $application, User $user, Student $student)
    {
        $this->application = $application;
        $this->user = $user;
        $this->student = $student;
    }

    public function build()
    {
        return $this->subject('Enrollment Approved - Welcome to Tugawe Elementary School!')
            ->view('emails.enrollment.approved');
    }
}
