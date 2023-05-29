<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
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

            $user->load('address');
            $user->setRelation('subscription', $user->subscription(config('cashier.subscription_name')));

            $data = [
                "auth_token" => $authToken,
                "user" => new UserResource($user),
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
}
