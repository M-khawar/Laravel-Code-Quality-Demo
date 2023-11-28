<?php

namespace App\Repositories;

use App\Contracts\Repositories\{LeadRepositoryInterface, UserRepositoryInterface};
use App\Notifications\{NewLeadNotification};
use App\Traits\CommonServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\{PageView, User};
use App\Packages\SendGridWrapper\SendGridInitializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeadRepository implements LeadRepositoryInterface
{
    use SendGridInitializer, CommonServices;

    private Model $leadModel;
    private User $userModel;
    private $userRepository;

    const FUNNEL_TYPES = [MASTER_FUNNEL, LIVE_OPPORTUNITY_CALL_FUNNEL];
    const FUNNEL_STEPS = [WELCOME_FUNNEL_STEP, WEBINAR_FUNNEL_STEP, CHECKOUT_FUNNEL_STEP, THANKYOU_FUNNEL_STEP];
    const ACCEPTED_FILTERABLE_ROLES = [ENAGIC_ROLE, TRIFECTA_ROLE, ADVISOR_ROLE, CORE_ROLE];

    public function __construct(Model $leadModel)
    {
        $this->leadModel = $leadModel;
        $this->userModel = app(User::class);
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function storeLead(string $affiliateCode, array $data)
    {
        $affiliate = $this->userModel::getAffiliateByCode($affiliateCode);

        $data = array_merge($data, [
            'advisor_id' => $affiliate->hasRole(ADVISOR_ROLE) ? $affiliate->id : $affiliate->advisor_id,
            'affiliate_id' => $affiliate->id,
        ]);

        $lead = $this->leadModel::firstOrCreate(
            ['email' => $data['email'], 'advisor_id' => $data['advisor_id']],
            $data
        );

        if ($lead->wasRecentlyCreated) {
            $listID = config('default_settings.sendgrid_masterclass_list');
            $nameArr = explode(" ", $lead->name);
            $data = [
                "first_name" => @$nameArr[0],
                "last_name" => @$nameArr[1],
                "email" => $lead->email,
                "custom_fields" => [
                    "w1_T" => $affiliate?->affiliate_code
                ],
            ];

            $this->sendgridContactManager()->addContact($listID, $data);

            $affiliate->setRelation('notifications', $this->userRepository->getNotifications($affiliate));
            $affiliate->notify((new NewLeadNotification($lead))->afterCommit());
        }

        return $lead;
    }

    public function storeLeadValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'instagram' => ['nullable'],
            'affiliate_code' => ['nullable', 'exists:' . User::class . ',affiliate_code'],
            'funnel_type' => ['required', Rule::in(self::FUNNEL_TYPES)],
        ]);
    }

    public function storePageVisit(string $affiliateCode, array $data)
    {
        $affiliate = $this->userModel::getAffiliateByCode($affiliateCode);
        $data = array_merge($data, ['affiliate_id' => $affiliate->id]);

        return PageView::create($data);
    }

    public function storePageVisitValidation(array $data)
    {
        return Validator::make($data, [
            'ip' => ['required', 'ip'],
            'funnel_type' => ['required', Rule::in(self::FUNNEL_TYPES)],
            'funnel_step' => ['required', Rule::in(self::FUNNEL_STEPS)],
            'affiliate_code' => ['nullable', 'exists:' . User::class . ',affiliate_code'],
        ]);
    }

    public function fetchLeads($funnelType, string $startDate, string $endDate, ?string $affiliateUuid = null, ?bool $paginated = true, ?bool $downLines = false, ?string $queryString = null, ?bool $adminStatsFilter = false)
    {
        $user = $affiliateUuid ? $this->userModel::findByUuid($affiliateUuid) : currentUser();
        $affiliateID = null;

        /**
         * Bypassing filter for admin when get leads for admin-dashboard
         */
        if (!$adminStatsFilter) {
            $affiliateID = $user?->id;
            throw_if(!$affiliateID, "Affiliate User not found.");
        }

        $query = $this->leadModel::query();

        $query->where(function ($query) use ($affiliateID, $funnelType, $downLines, $queryString) {
            $query->when(!empty($affiliateID), fn($q) => $q->where('affiliate_id', $affiliateID));
            $query->when($downLines, fn($q) => $q->orWhere('advisor_id', $affiliateID));
        });

        $query->when(!empty($funnelType), fn($q) => $q->where('funnel_type', $funnelType));
        $query->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString));

        /**
         * filtering records on `downlines` in case of admin-stats
         */
        $query->when(($adminStatsFilter && $downLines), fn($q) => $q->where('affiliate_id', $user->id)->orWhere('advisor_id', $user->id));


        $query->whereBetween("created_at", array($startDate, $endDate));
        $query->with('affiliate', 'advisor')->latest();

        return $paginated ? $query->paginate()->withQueryString() : $query->get();
    }

    public function fetchMembers($funnelType, string $startDate, string $endDate, string $affiliateUuid = null, ?bool $paginated = true, ?bool $downLines = false, ?string $queryString = null, ?string $filterableRole = null, ?bool $adminStatsFilter = false)
    {
        $user = $affiliateUuid ? $this->userModel::findByUuid($affiliateUuid) : currentUser();
        $affiliateID = null;

        /**
         * Bypassing filter for admin when get members for admin-dashboard
         */
        if (!$adminStatsFilter) {
            $affiliateID = $user?->id;
            throw_if(!$affiliateID, "Affiliate User not found.");
        }

        if (!empty($filterableRole)) {
            $filterableRole = Str::title($filterableRole);
            throw_if(!in_array($filterableRole, self::ACCEPTED_FILTERABLE_ROLES), "Role filter is invalid.");
            $filterableRole = $filterableRole != ENAGIC_ROLE ? $filterableRole : implode(",", [ENAGIC_ROLE, TRIFECTA_ROLE, ADVISOR_ROLE]);
        }

        $badgesAsString = $this->achievementBadgeOrder();
        $query = $this->userModel::query()->excludeAdmins();

        $query->selectRaw("*, (
        SELECT string_agg(roles.name, ',')
        from roles inner join model_has_roles on model_has_roles.role_id = roles.id
        where
            model_has_roles.model_id = users.id and model_has_roles.model_type='User'
            and roles.name in (" . $badgesAsString . ")
        group by
            model_has_roles.model_id
        ) as achieved_badges");

        $query->where(function ($query) use ($affiliateID, $funnelType, $downLines, $queryString, $filterableRole) {
            $query->when(!empty($affiliateID), fn($q) => $q->where('affiliate_id', $affiliateID));
            $query->when($downLines, fn($q) => $q->orWhere('advisor_id', $affiliateID));
        });

        $query->when(!empty($funnelType), fn($q) => $q->where('funnel_type', $funnelType));
        $query->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString));

        /**
         * filtering records on `downlines` in case of admin-stats
         */
        $query->when(($adminStatsFilter && $downLines), fn($q) => $q->where('affiliate_id', $user->id)->orWhere('advisor_id', $user->id));

        /**
         * Filtering members for admin-dashboard stats by roles
         * and role assigned time
         */
        $query->when(!empty($filterableRole), fn($q) => $q->whereHas("roles", function ($roleQuery) use ($filterableRole, $startDate, $endDate) {

            if (str_contains($filterableRole, ",")) {
                $filterableRole = explode(',', $filterableRole);
            } else {
                $filterableRole = [$filterableRole];
            }

            $roleQuery->whereIn("roles.name", $filterableRole)
                ->whereBetween(config('permission.table_names.model_has_roles') . ".created_at", array($startDate, $endDate));
        }));

        if (empty($filterableRole)) $query->whereBetween("created_at", array($startDate, $endDate));
        $query->with('affiliate', 'address', 'notificationSettings')->latest();

        return $paginated ? $query->paginate()->withQueryString() : $query->get();
    }

    public function deleteLead(string $uuid)
    {
        $lead = $this->leadModel::ByUUID($uuid)->firstOrFail();
        return $lead->delete();
    }

    public function fetchLeaderboard(string $startDate, string $endDate, ?string $queryString = null, ?int $perPage = 20)
    {
        $query = $this->userModel::query()->whereHas("roles", function ($q) {
            $q->whereIn("roles.name", [ENAGIC_ROLE]);
        });
    
        $query->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString));
    
        $leaderboardLevelOrder = $this->leaderboardLevelOrder();
    
        $query->selectRaw("*, (
            SELECT roles.name
            from roles inner join model_has_roles on model_has_roles.role_id = roles.id
            where
                model_has_roles.model_id = users.id and model_has_roles.model_type='User'
                and roles.name in (" . $leaderboardLevelOrder . ")
            ORDER BY CASE roles.name
               WHEN 'Trifecta' THEN 1
               WHEN 'Enagic' THEN 2
               ELSE 3
             END
    
             Limit 1
        ) as achieved_level");
    
        $query->with([
            'pageViews' => function ($q) use ($startDate, $endDate) {
                $q->select(DB::raw('count(distinct(ip))'))
                    ->where("funnel_step", WELCOME_FUNNEL_STEP)
                    ->where('funnel_type', MASTER_FUNNEL)
                    ->whereBetween("created_at", array($startDate, $endDate));
            },
            'leads' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween("created_at", array($startDate, $endDate));
            },
            'members' => function ($q) use ($startDate, $endDate) {
                $q->excludeAdmins()->whereBetween("created_at", array($startDate, $endDate));
            },
        ]);
    
        $query->withCount([
            "pageViews as visits_count" => function ($q) use ($startDate, $endDate) {
            },
            "leads" => function ($q) use ($startDate, $endDate) {
            },
            "members" => function ($q) use ($startDate, $endDate) {
            },
        ]);
    
        $query
            ->orderBy("members_count", "desc")
            ->orderBy("leads_count", "desc")
            ->orderBy("visits_count", "desc");
    
        return $query->paginate($perPage)->withQueryString();
    }
    // public function fetchLeaderboard(string $startDate, string $endDate, ?string $queryString = null, ?int $perPage = 20)
    // {
    //     $query = $this->userModel::query()->whereHas("roles", function ($q) {
    //         $q->whereIn("roles.name", [ENAGIC_ROLE]);
    //     });

    //     $query->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString));

    //     $leaderboardLevelOrder = $this->leaderboardLevelOrder();

    //     $query->selectRaw("*, (
    //     SELECT roles.name
    //     from roles inner join model_has_roles on model_has_roles.role_id = roles.id
    //     where
    //         model_has_roles.model_id = users.id and model_has_roles.model_type='User'
    //         and roles.name in (" . $leaderboardLevelOrder . ")
    //     ORDER BY CASE roles.name
    //        WHEN 'Trifecta' THEN 1
    //        WHEN 'Enagic' THEN 2
    //        ELSE 3
    //      END

    //      Limit 1
    //     ) as achieved_level");

    //     $query->withCount([
    //         "pageViews as visits_count" => function ($q) use ($startDate, $endDate) {
    //             $q->select(DB::raw('count(distinct(ip))'))
    //                 ->where("funnel_step", WELCOME_FUNNEL_STEP)
    //                 ->where('funnel_type', MASTER_FUNNEL)
    //                 ->whereBetween("created_at", array($startDate, $endDate));
    //         },

    //         "leads" => function ($q) use ($startDate, $endDate) {
    //             $q->whereBetween("created_at", array($startDate, $endDate));
    //         },

    //         "members" => function ($q) use ($startDate, $endDate) {
    //             $q->excludeAdmins()->whereBetween("created_at", array($startDate, $endDate));
    //         },

    //     ]);

    //     $query
    //         ->orderBy("members_count", "desc")
    //         ->orderBy("leads_count", "desc")
    //         ->orderBy("visits_count", "desc");

    //     return $query->paginate($perPage)->withQueryString();
    // }

    private function achievementBadgeOrder()
    {
        $badges = [ENAGIC_ROLE, TRIFECTA_ROLE, ADVISOR_ROLE];
        return sprintf("'%s'", implode("', '", $badges));
    }

    private function leaderboardLevelOrder()
    {
        $badges = [ENAGIC_ROLE, TRIFECTA_ROLE];
        return sprintf("'%s'", implode("', '", $badges));
    }
}
