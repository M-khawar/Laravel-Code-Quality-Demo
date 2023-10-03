<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportedRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public object $data;

    public function __construct(array $data)
    {
        $this->data = (object)$data;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $email = $this->data->email;

        return (new MailMessage)
            ->subject("New {$this->data->category} Support Request Received")
            ->line("From: {$email}")
            ->line("Subject: {$this->data->subject}")
            ->line("Message: {$this->data->message}");
    }

}
