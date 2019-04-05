<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMessage extends Notification
{
    use Queueable;


    public function __construct(User $to, User $from, $message)
    {
        $this->to     = $to;
        $this->from   = $from;
        $this->message= $message;
    }

   
    public function via($notifiable)
    {
        return ['mail'];
    }

    
    public function toMail($notifiable)
    {
        $subject = sprintf('Suggestion to teacher %s',  $this->from->name);
        $greeting = sprintf('%s: You\'ve got a new suggestion from %s!', $this->from->name, $this->to->name);
                
        $mailMessage = new MailMessage();
        $mailMessage->subject($subject)
                    ->greeting($greeting)
                    ->line('')
                    ->line($this->message);        

        return $mailMessage;
    }

    
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
