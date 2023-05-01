<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public $email;
    public $token;
    public $email_template;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $token, $email_template)
    {
        $this->email = $email;
        $this->token = $token;
        $this->email_template = $email_template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.common');
    }
}
