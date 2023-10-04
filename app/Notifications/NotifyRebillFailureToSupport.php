<?php

namespace App\Notifications;

use App\Packages\StripeWrapper\StripeFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyRebillFailureToSupport extends Notification implements ShouldQueue
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
        $price = StripeFactory::centToUsds($this->stripeData["plan"]["amount"]);
        return (new MailMessage)
            ->subject("R2F Rebill Failure")
            ->line("{$this?->member?->name} subscription rebill has failed.")
            ->line("Charge amount: $" . $price)
            ->line("Err:  {$this->stripeData['cancellation_details']}")
            ->line('Subscription Cancelled');
    }
}
