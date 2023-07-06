<?php

namespace App\Repositories;

use App\Contracts\Repositories\OnboardingRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
    protected $onboardingRepository;
    protected Model $user;

    public function __construct(Model $user)
    {
        $this->onboardingRepository = app(OnboardingRepositoryInterface::class);
        $this->user = $user;
    }

    public function getUserInfo(User $user): UserResource
    {
        $user->loadMissing(['address', 'profile']);
        $user->setRelation('onboardingStepsState', $this->onboardingRepository->onboardingStepsState($user));
        if (!isset($user->subscription)) {
            $user->setRelation('subscription', $user->subscription(config('cashier.subscription_name')));
        }

        return new UserResource($user);
    }
}
