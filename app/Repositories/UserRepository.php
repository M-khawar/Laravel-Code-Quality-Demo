<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Validator;
use App\Contracts\Repositories\{OnboardingRepositoryInterface, UserRepositoryInterface};
use App\Http\Resources\{RoleResource, UserPublicInfoResource, UserResource};
use App\Models\{User};
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
    protected $onboardingRepository;
    protected Model $user;
    private $roleModel;

    public function __construct(Model $user)
    {
        $this->onboardingRepository = app(OnboardingRepositoryInterface::class);
        $this->user = $user;
        $this->roleModel = app(config('permission.models.role'));
    }

    public function getUserInfo(User $user): UserResource
    {
        $user->loadMissing(['address', 'profile']);

        $user->loadMissing([
            'advisor.settings' => fn($q) => $q->settingFilters(['group' => ADVISOR_SETTING_GROUP]),
            'affiliate.settings' => fn($q) => $q->settingFilters(['group' => ADVISOR_SETTING_GROUP]),
        ]);

        $user->setRelation('onboardingStepsState', $this->onboardingRepository->onboardingStepsState($user));

        if (!array_key_exists('subscription', $user->toArray())) {
            $user->setRelation('subscription', $user->subscription(config('cashier.subscription_name')));
        }

        return new UserResource($user);
    }

    public function getUserPublicInfo(User $user): UserPublicInfoResource
    {
        $user->loadMissing(['address', 'profile']);

        $user->loadMissing([
            'advisor.settings' => fn($q) => $q->settingFilters(['group' => ADVISOR_SETTING_GROUP]),
            'affiliate.settings' => fn($q) => $q->settingFilters(['group' => ADVISOR_SETTING_GROUP]),
            'roles' => fn($q) => $q->where("name", "!=", ADMIN_ROLE),
        ]);

        return new UserPublicInfoResource($user);
    }

    public function getRolesExceptAdmin()
    {
        $roles = $this->roleModel::excludeAdminRole()->excludeAllMemberRole()->get();

        return RoleResource::collection($roles);
    }

    public function fetchReferral(?string $referralCode = null)
    {
        return $this->user::query()
            ->when(!empty($referralCode), fn($q) => $q->whereAffiliate($referralCode))
            ->when(empty($referralCode), fn($q) => $q->whereDefaultAdvisor())
            ->with('profile')
            ->firstOrFail();
    }

    public function fetchUsersIncludingAdmin(?string $queryString = null)
    {
        return $this->user::query()
            ->select('id', 'uuid', 'name', 'email', 'avatar')
            ->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString))
            ->paginate(20)->withQueryString();
    }

    public function updateAdministrator(array $data)
    {
        $userUuid = $data["user_uuid"];
        $advisorUuid = $data["advisor_uuid"];
        $affiliateUuid = $data["affiliate_uuid"];

        $currentUser = $this->user::findOrFailUserByUuid($userUuid);
        $advisor = $this->user::findOrFailUserByUuid($advisorUuid);
        $affiliate = $this->user::findOrFailUserByUuid($affiliateUuid);

        $currentUser->fill(["advisor_id" => $advisor->id, "affiliate_id" => $affiliate->id])->save();

        return $this->getUserPublicInfo($currentUser);
    }

    public function updateAdministratorValidation(array $data)
    {
        return Validator::make($data, [
            "user_uuid" => ["required", "string", 'exists:' . get_class($this->user) . ',uuid'],
            "advisor_uuid" => ["required", "string", 'exists:' . get_class($this->user) . ',uuid'],
            "affiliate_uuid" => ["required", "string", 'exists:' . get_class($this->user) . ',uuid'],
        ]);
    }

    public function assignRole(array $data)
    {
        $user = $this->user::findOrFailUserByUuid($data["user_uuid"]);
        $roleIds = $this->roleModel::byUUID($data["role_uuid"])->pluck("id")->toArray();

        $user->roles()->toggle($roleIds);

        $query = $user->roles()->whereNotIn("name", [ADMIN_ROLE, ALL_MEMBER_ROLE]);
        return $query->pluck("uuid")->toArray();
    }
}
