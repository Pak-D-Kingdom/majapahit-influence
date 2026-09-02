<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KolWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public function __construct(public User $user, public string $token) {}
    public function build(): self { return $this->subject('Akun Majapahit Influence Anda siap digunakan')->view('emails.kol-welcome'); }
}
