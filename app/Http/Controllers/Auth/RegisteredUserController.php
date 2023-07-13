<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Packages\StripeWrapper\StripeFactoryTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    use StripeFactoryTrait;

    public function __construct(public UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->registerValidation($request->input())->validate();
            $advisorId = $this->getReferral($request->affiliate_code)->id;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'instagram' => $request->instagram,
                'advisor_id' => $advisorId ?? config('default_settings.default_advisor'),
                'advisor_date' => now()->toDate(),
                'funnel_type' => $request->funnel_type,
            ]);
            $user->profile()->create([
                'display_name' => $user->name,
                'display_text' => __('messages.default_display_text'),
            ]);

            $user->address()->create([
                'city' => $request->input('address.city'),
                'state' => $request->input('address.state'),
                'zipcode' => $request->input('address.zipcode'),
                'address' => $request->input('address.address'),
            ]);

            $dataForSubscription = $request->subscription;
            $subscription = $this->buySubscription()->handle($user, $dataForSubscription);
            $user->setRelation('subscription', $subscription);

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            $token = $request->user()->createToken(config('sanctum.token_name'));
            $authToken = $token->plainTextToken;

            $data = [
                "auth_token" => $authToken,
                "user" => $this->userRepository->getUserInfo($user),
            ];

            return response()->success(__('auth.register.success'), $data);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }

    }

    private function getReferral($affiliateCode)
    {
        return User::whereAffiliate($affiliateCode)->first();
    }

    protected function registerValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required'],
            'affiliate_code' => ['required', 'exists:' . User::class . ',affiliate_code'],
            'funnel_type' => ['required', Rule::in([MASTER_FUNNEL, LIVE_OPPORTUNITY_CALL_FUNNEL])],
            'instagram' => ['nullable'],
            'address.city' => ['required'],
            'address.state' => ['required'],
            'address.zipcode' => ['required'],
            'address.address' => ['required'],
            'subscription.plan_id' => ['required', 'exists:' . SubscriptionPlan::class . ',uuid'],
            'subscription.payment_method_id' => ['required'],
        ]);
    }

    public function stepwiseValidation(Request $request)
    {
        try {

            $step = $request->step;
            $data = $request->input();
            $validator = null;

            /**
             * validating {step} enum
             */
            Validator::validate(
                $data,
                ['step' => ['required', 'in:USER_INFO,ADDRESS_INFO']],
                ['step.in' => __('auth.step_enum.invalid')]
            );

            /**
             * step data validation
             */
            switch ($step) {
                case 'USER_INFO':
                    $validator = $this->userinfoStepValidation($data);
                    break;

                case 'ADDRESS_INFO':
                    $validator = $this->addressStepValidation($data);
                    break;

                default:
                    $validator = $this->userinfoStepValidation($data);
            }

            $validator->validate();

            return response()->message(__('auth.step_validation.accepted', ['step_name' => $step]));

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    protected function userinfoStepValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required'],
            'affiliate_code' => ['required', 'exists:' . User::class . ',affiliate_code'],
            'instagram' => ['nullable'],
        ]);
    }

    protected function addressStepValidation(array $data)
    {
        return Validator::make($data, [
            'city' => ['required'],
            'state' => ['required'],
            'zipcode' => ['required'],
            'address' => ['required'],
            'payment_method_id' => ['required'],
        ]);
    }

}
