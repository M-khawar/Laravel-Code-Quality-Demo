<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Repositories\OnboardingRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(public OnboardingRepositoryInterface $onboardingRepository)
    {
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();

            $user = $request->user();
            $token = $user->createToken(config('sanctum.token_name'));
            $authToken = $token->plainTextToken;

            $data = [
                "auth_token" => $authToken,
                "exp" => config('sanctum.expiration'),
                "user" => $this->userResponse($user),
            ];

            return response()->success(__('auth.login.success'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function currentUserInfo()
    {
        try {
            $user = currentUser();
            $data = ["user" => $this->userResponse($user)];

            return response()->success(__('auth.current_user_info.fetched'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    protected function userResponse(User $user): UserResource
    {
        $user->load('address');
        $user->setRelation('subscription', $user->subscription(config('cashier.subscription_name')));
        $user->setRelation('onboardingStepsState', $this->onboardingRepository->onboardingStepsState($user));
        return new UserResource($user);
    }
}
