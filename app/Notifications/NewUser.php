<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUser extends Notification
{
    use Queueable;


    public function __construct($password = null)
    {
        $this->password = $password;
    }

   
    public function via($notifiable)
    {
        return ['mail'];
    }

    
    public function toMail($notifiable)
    {
        $subject = sprintf('Welcome to the %s community!', config('app.name'));
        $greeting = sprintf('Hello %s!', $notifiable->name);
      
        $mailMessage = new MailMessage();

        $mailMessage->subject($subject)
                    ->greeting($greeting)
                    ->line("We really appreciate your support and can’t wait to share with you all the new projects we have planned.")
                    ->line("access your account using the temporary password <strong>{$this->password}</strong>, We remind you for security reasons update it.")
                    ->line("We hope you enjoy your experience with us.");        

        return $mailMessage;
    }

    
    public function toArray($notifiable)
    {
        return [];
    }
}
