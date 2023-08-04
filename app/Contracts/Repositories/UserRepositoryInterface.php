<?php

namespace App\Contracts\Repositories;

use App\Http\Resources\{UserPublicInfoResource, UserResource};
use App\Models\User;

interface UserRepositoryInterface
{
    public function getUserInfo(User $user): UserResource;

    public function getUserPublicInfo(User $user): UserPublicInfoResource;

    public function getRolesExceptAdmin();

    public function fetchReferral(?string $referralCode = null);

    public function fetchUsersIncludingAdmin(?string $queryString = null);
}
