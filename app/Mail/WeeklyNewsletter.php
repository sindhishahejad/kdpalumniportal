<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WeeklyNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $successStories;
    public $jobs;
    public $notices;

    public function __construct(Collection $successStories, Collection $jobs, Collection $notices)
    {
        $this->successStories = $successStories;
        $this->jobs = $jobs;
        $this->notices = $notices;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KDP Alumni Portal - Weekly Digest & Newsletter',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter',
        );
    }
}