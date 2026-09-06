<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KolApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $kolName,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KOL Anda Telah Disetujui',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.kol.approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}