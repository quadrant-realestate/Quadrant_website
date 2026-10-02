<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewInquiry extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public object $inquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New website inquiry: ' . $this->inquiry->name,
            replyTo: [new Address($this->inquiry->email, $this->inquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-inquiry');
    }
}
