<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class SendSupportAboutGracePeriodNotification extends Notification
{
    use Queueable;


    private $member;
    private $stripeData;

    public function __construct($member, $stripeData)
    {
        $this->member = $member;
        $this->stripeData = $stripeData;

        info("Asdfasd", [$member]);
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $roles = $this->member->roles->pluck("name")->toArray();

        return (new MailMessage)
            ->subject("Subscription Cancellation")
            ->line("{$this->member->name} <{$this->member->email}> has cancelled their membership.")
            ->line("Their subscription remains active until " . Carbon::createFromTimestamp($this->stripeData['cancel_at'])->format('M d, Y g:i A'))
            ->line("Affiliate: {$this->member?->affiliate?->name}")
            ->line("Advisor: {$this->member?->advisor?->name}")
            ->line("Enagic: " . in_array(ENAGIC_ROLE, $roles))
            ->line("Trifecta:" . in_array(TRIFECTA_ROLE, $roles))
            ->line("Advisor:" . in_array(ADVISOR_ROLE, $roles));
    }

}
