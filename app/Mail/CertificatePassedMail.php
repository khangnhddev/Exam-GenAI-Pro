<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\ExamAttempt;

class CertificatePassedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected ExamAttempt $attempt
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Congratulations! You've Passed {$this->attempt->exam->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate-passed',
            with: [
                'attempt' => $this->attempt,
                'user' => $this->attempt->user,
                'exam' => $this->attempt->exam,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
