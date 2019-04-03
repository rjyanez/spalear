<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($issuer, $content)
    {
        $this->issuer  = $issuer;
        $this->user    = $content['user'];
        $this->data = $content['message'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Suggestions to teacher {$this->user['name']}")
                    ->view('emails.message',[
                        'date'      => date("Y/m/d"),
                        'issuer'    => $this->issuer,
                        'user'      => $this->user,
                        'data'   => $this->data
                    ]);
    }
}
