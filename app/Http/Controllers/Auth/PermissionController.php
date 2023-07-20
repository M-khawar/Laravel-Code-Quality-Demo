<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(public UserRepositoryInterface $userRepository)
    {
    }

    public function roles()
    {
        try {
            $data = $this->userRepository->getRolesExceptAdmin();

            return response()->success(__('auth.roles.fetched'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
