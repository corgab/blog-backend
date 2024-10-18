<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Config;


class Welcome extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationToken;

    public function __construct($user, $verificationToken)
    {
        $this->user = $user;
        $this->verificationToken = $verificationToken;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Benvenuto!',
        );
    }

    public function content(): Content
    {
        $verificationUrl = env('FRONTEND_URL') . '/verify-email?token=' . $this->verificationToken . '&email=' . urlencode($this->user->email);

    
        return new Content(
            view: 'mail.welcome',
            with: [
                'userName' => $this->user->name,
                'verificationUrl' => $verificationUrl,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
