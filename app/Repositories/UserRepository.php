<?php

namespace App\Repositories;

use App\Contracts\Repositories\{OnboardingRepositoryInterface, UserRepositoryInterface};
use App\Http\Resources\{RoleResource, UserResource};
use App\Models\{Role, User};
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

        $user->loadMissing([
            'advisor.settings' => fn($q) => $q->settingFilters(['group' => ADVISOR_SETTING_GROUP]),
        ]);

        $user->setRelation('onboardingStepsState', $this->onboardingRepository->onboardingStepsState($user));

        if (!array_key_exists('subscription', $user->toArray())) {
            $user->setRelation('subscription', $user->subscription(config('cashier.subscription_name')));
        }

        return new UserResource($user);
    }

    public function getRolesExceptAdmin()
    {
        $roles = Role::excludeAdminRole()->get();

        return RoleResource::collection($roles);
    }
}
