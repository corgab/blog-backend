<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;


class Welcome extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Benvenuto!',
        );
    }

    public function content(): Content
    {
        // $verificationUrl = env('FRONTEND_URL') . '/verify-email?token=' . $this->verificationToken . '&email=' . urlencode($this->user->email);
        $verificationUrl = $this->verificationUrl($this->user);
    
        return new Content(
            view: 'mail.welcome',
            with: [
                'email' => $this->user->email,
                'userName' => $this->user->name,
                'verificationUrl' => $verificationUrl,
            ]
        );
    }

    protected function verificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );
    }
    

    public function attachments(): array
    {
        return [];
    }
}
