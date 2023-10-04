<?php

namespace App\Listeners;

use App\Models\User;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Notifications\SubscriptionConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;

class StripeListener
{

    private mixed $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'invoice.payment_succeeded' && $event->payload["data"]["object"]["billing_reason"] == "manual") {
            $data = $this->buildPaymentPayload($event->payload);
            $user = $this->getStripeUser($data["customer"]);
            if ($user) $user->notify(new SubscriptionConfirmedNotification($data));
        }


        if ($event->payload['type'] === 'invoice.payment_succeeded' &&
            $event->payload["data"]["object"]["billing_reason"] == "subscription_create" &&
            isset($event->payload["data"]["object"]["subscription"])
        ) {
            $data = $this->buildPaymentPayload($event->payload);
            $user = $this->getStripeUser($data["customer"]);
            if ($user) $user->notify(new SubscriptionConfirmedNotification($data));
        }


        if ($event->payload['type'] === "customer.subscription.updated"
            && isset($event->payload["data"]["object"]['cancel_at_period_end'])
            && $event->payload["data"]["object"]['cancel_at_period_end']
        ) {
            $data = $this->buildPaymentPayload($event->payload);
            info("grace period", $data);
        }

        if ($event->payload['type'] === "customer.subscription.deleted") {
            $data = $this->buildPaymentPayload($event->payload);
            info("cacelled subscription", $data);
        }
    }

    private function buildPaymentPayload(array $data)
    {
        $data = $data["data"]["object"];

        return [
            "currency" => @$data["currency"],
            "customer" => @$data["customer"],
            "customer_email" => @$data["customer_email"],
            "customer_name" => @$data["customer_name"],
            "invoice_pdf" => @$data["invoice_pdf"],
            "billing_reason" => @$data["billing_reason"],
            "payment_intent" => @$data["payment_intent"],
            "amount_paid" => @$data["amount_paid"],
            "subscription" => @$data["subscription"],
            "subtotal" => @$data["subtotal"],
            "total" => @$data["total"],
            "plan" => @$data["lines"]["data"][0]["plan"],
        ];
    }

    private function getStripeUser(string $stripeId)
    {
        $user = User::where('stripe_id', $stripeId)->first();

        if ($user) {
            $user->setRelation('notifications', $this->userRepository->getNotifications($user));
        }

        return $user;
    }
}
