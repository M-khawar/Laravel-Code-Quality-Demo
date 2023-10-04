<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyRebillFailureToAdministrator extends Notification implements ShouldQueue
{
    use Queueable;

    private $member;
    private $stripeData;


    public function __construct($member, $stripeData)
    {
        $this->member = $member;
        $this->stripeData = $stripeData;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $affiliateName = $this->member?->affiliate?->name;
        $advisorName = $this->member?->advisor?->affiliate?->name;

        return (new MailMessage)
            ->subject("Your Team Member Needs Your Help!!!")
            ->line("Hey,  we wanted to let you know that the member {$this->member->name} payment didn't go through.")
            ->line("We sent this email to the affiliate {$affiliateName} & advisor {$advisorName} for this member.");
    }
}
