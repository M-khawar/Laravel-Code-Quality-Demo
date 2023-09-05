<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use App\Contracts\Repositories\{ProfileRepositoryInterface, UserRepositoryInterface};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response;

class ProfileRepository implements ProfileRepositoryInterface
{
    private Model $userModel;
    private $userRepository;

    public function __construct(Model $userModel)
    {
        $this->userModel = $userModel;
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function updateProfile(array $data)
    {
        $user = currentUser();
        $user->update($data);

        $paypal = @$data["paypal"] ?? "";
        $user->updateProperty(ACCOUNT_SETTING_GROUP, PAYPAL_ACCOUNT_SETTING, $paypal);

        return $this->userRepository->getUserInfo($user);

    }

    public function updateProfileValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string'],
            'instagram' => ['nullable', 'string'],
            'paypal' => ['nullable', 'string'],
        ]);
    }

    public function updatePassword(array $data)
    {
        $user = currentUser();
        $password = $data["password"];

        $validPassword = Hash::check($data["old_password"], $user->password);

        abort_if(!$validPassword, Response::HTTP_UNPROCESSABLE_ENTITY, __("auth.password_not_matched"));

        $passwordHash = Hash::make($password);
        return $user->fill(["password" => $passwordHash])->save();
    }

    public function updatePasswordValidation(array $data)
    {
        return Validator::make($data, [
            'old_password' => ['required', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    }
}
