<?php

namespace App\Notifications;

use App\Classes;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewClass extends Notification
{
    use Queueable;


    public function __construct(Classes $class)
    {
        $this->class = $class;
    }

   
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    
    public function toMail($notifiable)
    {
        $subject = sprintf('%s: You\'ve got a new class from %s!', config('app.name'), $this->class->student->name);
        $greeting = sprintf('Hello %s!', $notifiable->name);
        $message = sprintf(
            'The student strong>%s</strong> has scheduled a class <strong>%s</strong>, %s for the <strong>%s</strong>',
             $this->class->student->name,
             $this->class->type->value,
             (!empty($this->class->lesson)) ? "selecting Lesson <strong>{$this->class->lesson->name}</strong> of the <strong>{$this->class->lesson->level->value}</strong> level," : "" ,
             $this->class->date
        );
        
        $mailMessage = new MailMessage();

        $mailMessage->subject($subject)
                    ->greeting($greeting)
                    ->line('You have received a new class reservation.')
                    ->line($message);        

        return $mailMessage;
    }

    
    public function toArray($notifiable)
    {
        return [
            'subject' =>'New Class',
            'message' => sprintf(
                '%s has scheduled a class %s, %s for the %s',
                 $this->class->student->name,
                 $this->class->type->value,
                 (!empty($this->class->lesson)) ? "Lesson {$this->class->lesson->name} of the {$this->class->lesson->level->value} level," : "" ,
                 $this->class->date
            )
        ];
    }
}
