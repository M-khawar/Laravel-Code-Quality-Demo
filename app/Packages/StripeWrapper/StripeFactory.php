<?php

namespace App\Packages\StripeWrapper;

use Stripe\StripeClient;

class StripeFactory
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config("cashier.secret"));
    }

    public static function __callStatic($method, $parameters)
    {
        throw_if(!in_array($method, ['usdToCents', 'centToUsds']), "Method {$method} not found in " . static::class);

        return self::$method(...$parameters);
    }

    public function createPaymentMethod()
    {
        return $this->stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 2,
                'exp_year' => 2025,
                'cvc' => '314',
            ],
        ]);
    }

    public function createClientSecret()
    {
        return $this->stripe->setupIntents->create([
            'payment_method_types' => ['card'],
        ]);
    }

    public function createStripeProductPrice(array $input)
    {

        //create stripe product
        $stripe_product = $this->stripe->products->create([
            'name' => $input['product_name'],
        ]);


        //create stripe price
        return $this->stripe->prices->create(array(
            "unit_amount" => self::usdToCents($input['amount']),
            'recurring' => ['interval' => $input['interval']],
            "currency" => "usd",
            "nickname" => $input['product_name'],
            "product" => $stripe_product->id
        ));

    }

    public function retriveSubscription($subscription_id)
    {

        $subscription = $this->stripe->subscriptions->retrieve(
            $subscription_id,
            []
        );

        return $subscription;
    }

    public function updateSubscription($subscription_id, $options = [])
    {
        return $this->stripe->subscriptions->update(
            $subscription_id,
            $options
        );
    }

    protected static function usdToCents($usd)
    {
        return round($usd * 100);
    }

    protected static function centToUsds($cents)
    {
        return round($cents / 100, 2);
    }
}
