<?php

namespace App\Notifications;

use App\Packages\StripeWrapper\StripeFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class SubscriptionConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $stripeData;

    public function __construct($stripeData)
    {
        $this->stripeData = $stripeData;
    }

    public function via($notifiable)
    {
        return ["mail", TwilioChannel::class];
    }

    public function toMail($notifiable)
    {
        $price = StripeFactory::centToUsds($this->stripeData['plan']['amount']);

        return (new MailMessage)
            ->subject("Welcome To RaceToFreedom")
            ->line("Welcome to the Race To Freedom community.")
            ->line("You have successfully joined and created an account. Here are your order details:")
            ->line("Race To Freedom Membership - $" . $price)
            ->action('Log into the platform', config('app.frontend_url') . "/login")
            ->line("Watch the welcome video and start the onboarding process")
            ->line("Check to make sure the emails we are sending are not in your spam folder")
            ->line("If you have any questions at all, please send an email to " . env("SUPPORT_EMAIL"))
            ->line("Thanks,");
    }

    public function toTwilio($notifiable)
    {
        $content = "Congrats on signing up with Race To Freedom Academy!\n" .
            "Your Success is Our Goal!!!\n\n" .
            "Your next steps:\n" .
            "1. Log in to the platform \n" .
            "2. Watch The Welcome Video\n\n" .
            "If you have any questions or problems logging in\n" .
            "Send an email to " . env("SUPPORT_EMAIL");

        return (new TwilioSmsMessage())->content($content);
    }
}
