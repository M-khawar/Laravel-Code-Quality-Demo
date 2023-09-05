<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProfileRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

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
}
