<?php

namespace App\Listeners;

use Closure;
use App\Models\User;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Notifications\{
    SendSupportAboutGracePeriodNotification,
    SubscriptionConfirmedNotification,
};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Laravel\Cashier\Events\WebhookReceived;

class StripeListener
{

    private mixed $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }


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
            $member = $this->getStripeUser($data["customer"], function ($q) {
                $q->with([
                    'affiliate', 'advisor',
                    'roles' => fn($q) => $q->whereNotIn("name", [ADMIN_ROLE, ALL_MEMBER_ROLE])
                ]);
            });

            if ($member) Notification::route('mail', env("SUPPORT_EMAIL"))
                ->notify(new SendSupportAboutGracePeriodNotification($member, $data));
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
            "cancel_at" => @$data["cancel_at"],
            "canceled_at" => @$data["canceled_at"],
        ];
    }

    private function getStripeUser(string $stripeId, Closure $closureQuery = null)
    {
        $query = User::where('stripe_id', $stripeId);

        if ($closureQuery instanceof Closure) {
            $closureQuery($query);
        }

        $user = $query->first();

        if ($user) {
            $user->setRelation('notifications', $this->userRepository->getNotifications($user));
        }

        return $user;
    }
}
