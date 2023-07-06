<?php

namespace App\Contracts\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;

interface UserRepositoryInterface
{
    public function getUserInfo(User $user): UserResource;

}
