<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Resources\{ReferralResource, UserListResource};
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(public UserRepositoryInterface $userRepository)
    {
    }

    public function getReferral()
    {
        try {
            $referralCode = request()->input('referral');
            $user = $this->userRepository->fetchReferral($referralCode);

            $referral = new ReferralResource($user);
            return response()->success(__('messages.referral.found'), $referral);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getUsers()
    {
       
        try {
            $query = request()->input('query');
            $filterAdvisors = request()->boolean('filter_advisors');
            $users = $this->userRepository->fetchUsersIncludingAdmin($query, $filterAdvisors);
            $users = (UserListResource::collection($users))->response()->getData(true);

            return response()->success(__('messages.users.fetched'), $users);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
