<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\{TwilioChannel, TwilioSmsMessage};

class NewLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $lead;

    public function __construct($lead)
    {
        $this->lead = $lead;
    }


    public function via($notifiable)
    {
        $channels = [];

        if ($notifiable->notifications["lead_email"]) $channels[] = "mail";
        if ($notifiable->notifications["lead_sms"]) $channels[] = TwilioChannel::class;

        return $channels;
    }

    public function toMail($notifiable)
    {
        $instagram = $this->lead?->instagram;

        $mailer = (new MailMessage)
            ->subject("New Lead!")
            ->line('You have a new lead!')
            ->line($this->lead->name)
            ->line($this->lead->email);

        if ($instagram) {
            $mailer->action($instagram, "https://instagram.com/$instagram");
        }

        return $mailer;
    }


    public function toTwilio($notifiable)
    {
        $instagram = $this->lead?->instagram;

        $content = "New Lead!\n{$this->lead->name}\n{$this->lead->email}\n";
        if ($instagram) $content .= "https://instagram.com/$instagram";

        return (new TwilioSmsMessage())->content($content);
    }
}
