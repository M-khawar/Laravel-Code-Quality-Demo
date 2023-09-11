<?php

namespace App\Packages\StripeWrapper\CardActions;

use App\Models\User;
use App\Packages\StripeWrapper\Contracts\{DeleteOldCardOnUpdate};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AddPaymentCardAction
{
    public function handle(User $user, array $data)
    {
        $validated = $this->paymentMethodValidation($data)->validate();

        $paymentMethodId = $validated["payment_method_id"];

        //get existing stripe customer or create new if not available
        $user->createOrGetStripeCustomer();

        //attach payment method with customer and set it as default payment method
        $user->addPaymentMethod($paymentMethodId);

        if ($user instanceof DeleteOldCardOnUpdate) {
            $user->paymentMethods()->each->delete();
        }

        $user->updateDefaultPaymentMethod($paymentMethodId);

        return $this->returnableResponse($user);
    }

    protected function returnableResponse(Model $user): array
    {
        return [
            'card' => [
                'type' => @$user->pm_type,
                'last_four' => $user->pm_last_four ? Str::of($user->pm_last_four)->padLeft(19, '**** ') : null,
            ],
        ];
    }

    protected function paymentMethodValidation(array $data)
    {
        return Validator::make($data, [
            'payment_method_id' => ['required', 'string']
        ]);
    }
}
