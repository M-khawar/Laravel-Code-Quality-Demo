<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'instagram' => $request->instagram,
            ]);

            $user->address()->create([
                'city' => $request->input('address.city'),
                'state' => $request->input('address.state'),
                'zipcode' => $request->input('address.zipcode'),
                'address' => $request->input('address.address'),
            ]);

            DB::commit();

            event(new Registered($user));

            /*Auth::login($user);

            $token = $request->user()->createToken(config('sanctum.token_name'));
            $authToken = $token->plainTextToken;

            $data = [
                "auth_token" => $authToken,
                "user" => $user,
            ];*/

            return response()->message(__('auth.register.success'));

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }

    }

    protected function registerValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required'],
            'instagram' => ['nullable'],
            'address.city' => ['required'],
            'address.state' => ['required'],
            'address.zipcode' => ['required'],
            'address.address' => ['required'],
        ]);
    }
}
