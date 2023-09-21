<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Validator;
use App\Contracts\Repositories\{OnboardingRepositoryInterface, UserRepositoryInterface};
use App\Http\Resources\{RoleResource, UserPublicInfoResource, UserResource};
use App\Models\{Setting, User};
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
    protected $onboardingRepository;
    protected Model $user;
    private $roleModel;
    private $permissionModel;
    private mixed $settingsModel;

    public function __construct(Model $user)
    {
        $this->onboardingRepository = app(OnboardingRepositoryInterface::class);
        $this->user = $user;
        $this->roleModel = app(config('permission.models.role'));
        $this->permissionModel = app(config('permission.models.permission'));
        $this->settingsModel = app(Setting::class);
    }

    public function __call($method, $parameters)
    {
        throw_if(!in_array($method, ['getNotifications']), "Method {$method} not found in " . static::class);

        return $this->$method(...$parameters);
    }

    protected function getNotifications(?Model $user = null): array
    {
        $user = $user ?? auth()->user();
        $notifications = $user->getPropertiesInGroup(NOTIFICATION_SETTING_GROUP);

        $notificationsData = [];
        foreach ($notifications as $notification) {
            $notificationsData = array_merge($notificationsData, [$notification->name => (bool)$notification->value]);
        }

        return $notificationsData;
    }

    protected function getPermissions(User $user): array
    {
        if ($user->hasRole(ADMIN_ROLE)) {
            return $this->permissionModel::orderBy("name", "asc")->pluck("name")->toArray();
        } else {
            return $user->getAllPermissions()->pluck("name")->toArray();
        }
    }

    public function getUserInfo(User $user): UserResource
    {
        $user->loadMissing(['address', 'profile'])->append("has_active_subscription");

        $user->loadMissing([
            'advisor.settings' => fn($q) => $q->settingFilters(group: ADVISOR_SETTING_GROUP),
            'affiliate.settings' => fn($q) => $q->settingFilters(group: ADVISOR_SETTING_GROUP),
            'settings' => fn($q) => $q->settingFilters(group: [ACCOUNT_SETTING_GROUP, ADVISOR_SETTING_GROUP, PROMOTE_GROUP]),
        ]);

        $user->setRelation('onboardingStepsState', $this->onboardingRepository->onboardingStepsState($user));

        if (!array_key_exists('subscription', $user->toArray())) {
            $user->setRelation('subscription', $user->subscription(config('cashier.subscription_name')));
        }

        $user->setRelation('permissions', $this->getPermissions($user));
        $user->setRelation('notifications', $this->getNotifications());

        if ($user->hasRole(ADMIN_ROLE)){
            $adminSettings= $this->settingsModel::settingFilters(group: ADMIN_SETTINGS_GROUP)->get();
            $user->profile->setRelation("adminSettings", $adminSettings);
        }

        return new UserResource($user);
    }

    public function getUserPublicInfo(User $user): UserPublicInfoResource
    {
        $user->loadMissing(['address', 'profile']);
        $user->append("has_active_subscription");

        $user->loadMissing([
            'advisor.settings' => fn($q) => $q->settingFilters(group: ADVISOR_SETTING_GROUP),
            'affiliate.settings' => fn($q) => $q->settingFilters(group: ADVISOR_SETTING_GROUP),
            'roles' => fn($q) => $q->where("name", "!=", ADMIN_ROLE),
        ]);

        $user->setRelation('onboardingStepsState', $this->onboardingRepository->onboardingStepsState($user));

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

    public function fetchUsersIncludingAdmin(?string $queryString = null, ?bool $filterAdvisor = false)
    {
        return $this->user::query()
            ->select('id', 'uuid', 'name', 'email')
            ->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString))
            ->when($filterAdvisor, function($q){
                $q->whereDefaultAdvisor()->orWhereHas("roles", fn($q) => $q->where("name", ADVISOR_ROLE));
            })
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
