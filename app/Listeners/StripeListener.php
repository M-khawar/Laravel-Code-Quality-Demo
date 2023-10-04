<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;

class StripeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            info("trail payment", $data);
        }

        if ($event->payload['type'] === 'invoice.payment_succeeded' &&
            $event->payload["data"]["object"]["billing_reason"] == "subscription_create" &&
            isset($event->payload["data"]["object"]["subscription"])
        ) {
            $data = $this->buildPaymentPayload($event->payload);
            info("Subscribed", $data);
        }

        if ($event->payload['type'] === "customer.subscription.updated"
            && isset($event->payload["data"]["object"]['cancel_at_period_end'])
            && $event->payload["data"]["object"]['cancel_at_period_end']
        ) {
            $data = $this->buildPaymentPayload($event->payload);
            info("grace period", $data);
        }

        if ($event->payload['type'] === "customer.subscription.deleted"){
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
}
