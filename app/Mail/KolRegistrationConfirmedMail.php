<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KolRegistrationConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $kolName,
        public ?string $registrationNumber = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Registrasi KOL',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.kol.registration_confirmed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}