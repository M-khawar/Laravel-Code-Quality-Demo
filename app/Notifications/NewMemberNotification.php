<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\{TwilioChannel, TwilioSmsMessage};

class NewMemberNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $member;

    public function __construct($member)
    {
        $this->member = $member;
    }

    public function via($notifiable)
    {
        $channels = [];

        if ($notifiable->notifications["mem_email"]) $channels[] = "mail";
        if ($notifiable->notifications["mem_sms"]) $channels[] = TwilioChannel::class;

        return $channels;
    }

    public function toMail($notifiable)
    {
        $instagram = $this->member?->instagram;

        $mailer = (new MailMessage)
            ->subject("New Member!")
            ->line("Name: " . $this->member?->name)
            ->line("Email: " . $this->member?->email)
            ->line("Phone#: " . $this->member?->phone ?? "--");

        if ($instagram) {
            $mailer->action($instagram, "https://instagram.com/$instagram");
        }

        $mailer->line("Make sure to reach out to {$this->member?->name} with a message and say hi.");

        return $mailer;
    }

    public function toTwilio($notifiable)
    {
        $instagram = $this->member?->instagram;

        $content = "New Member!\n{$this->member?->name}\n{$this->member?->email}\n";
        if ($instagram) $content .= "https://instagram.com/$instagram";

        return (new TwilioSmsMessage())->content($content);
    }
}
