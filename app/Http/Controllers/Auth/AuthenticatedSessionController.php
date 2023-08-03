<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{LoginRequest, ShortAuthTokenRequest};
use App\Models\User;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function __construct(public UserRepositoryInterface $userRepository)
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
            $token = $user->createToken(config(
                'sanctum.token_name'),
                expiresAt: now()->addMinutes(config('sanctum.expiration'))
            );
            $authToken = $token->plainTextToken;

            $data = [
                "auth_token" => $authToken,
                "exp" => config('sanctum.expiration'),
                "user" => $this->userRepository->getUserInfo($user),
            ];

            return response()->success(__('auth.login.success'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {
            $user = currentUser();
            $user->tokens()->delete();

            return response()->message(__('auth.logout.success'));

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function currentUserInfo()
    {
        try {
            $user = currentUser();
            $data = ["user" => $this->userRepository->getUserInfo($user)];

            return response()->success(__('auth.current_user_info.fetched'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function userInfoByUuid($uuid)
    {
        try {
            $user = User::findOrFailUserByUuid($uuid);
            $data = ["user" => $this->userRepository->getUserPublicInfo($user)];

            return response()->success(__('auth.current_user_info.fetched'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function authByIdentity(ShortAuthTokenRequest $request)
    {
        try {
            $request->authenticate();

            $user = $request->user();

            $token = $user->createToken(
                config('sanctum.short_term_token_name'),
                expiresAt: now()->addMinutes(config('sanctum.short_expiration'))
            );
            $authToken = $token->plainTextToken;

            $data = [
                "auth_token" => $authToken,
                "exp" => config('sanctum.short_expiration'),
            ];

            return response()->success(__('auth.login.success'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
