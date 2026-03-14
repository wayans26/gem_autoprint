<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class reset_password_mail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $token;
    public $name;
    public function __construct($token, $name)
    {
        //
        $this->token = $token;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject("Reset Password")
            ->from(config('mail.mailers.smtp.from', 'no.replydev.reg-gemindonesia.net'), "Support@AtraxSys")
            ->view('Email.reset_password', [
                'name'      => $this->name,
                'resetUrl'  => $this->token
            ]);
    }
}
