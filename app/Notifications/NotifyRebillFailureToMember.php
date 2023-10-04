<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyRebillFailureToMember extends Notification implements ShouldQueue
{
    use Queueable;

    private $stripeData;

    public function __construct($stripeData)
    {
        $this->stripeData = $stripeData;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("You need to fix this ASAP!!!")
            ->line("I know you want to change your life this year. We want to keep helping you achieve your goals. Unfortunately your card was just declined.")
            ->line("In order to get access to racetofreedom.com, the FB groups, LIVE zoom calls, & telegram group.")
            ->line("You will need to update your credit card and file in the next 24 hours.")
            ->line("If you wait past 24 hours our team will remove you from all groups.")
            ->line("If you waited past 24 hours and you updated your card:")
            ->line("Please email support@racetofreedom.com to be added to all groups again.")
            ->line("See you on the next training,")
            ->line("Regards")
            ->salutation(" Colten Echave,");
    }
}
