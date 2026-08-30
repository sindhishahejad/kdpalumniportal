<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MentorshipRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $student, public string $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Mentorship Request on KDP Portal',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mentorship-request',
        );
    }
}