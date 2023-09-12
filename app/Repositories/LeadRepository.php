<?php

namespace App\Repositories;

use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Traits\CommonServices;
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

    const FUNNEL_TYPES = [MASTER_FUNNEL, LIVE_OPPORTUNITY_CALL_FUNNEL];
    const FUNNEL_STEPS = [WELCOME_FUNNEL_STEP, WEBINAR_FUNNEL_STEP, CHECKOUT_FUNNEL_STEP, THANKYOU_FUNNEL_STEP];

    public function __construct(Model $leadModel)
    {
        $this->leadModel = $leadModel;
        $this->userModel = app(User::class);
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

    public function fetchLeads($funnelType, string $startDate, string $endDate, ?string $affiliateUuid = null, ?bool $paginated = true, ?bool $downLines = false, ?string $queryString = null)
    {
        $affiliateID = $affiliateUuid ? $this->userModel::findByUuid($affiliateUuid)?->id : null;

        throw_if($affiliateUuid && !$affiliateID, "Affiliate User not found.");

        $query = $this->leadModel::query();

        $query->where(function ($query) use ($affiliateID, $funnelType, $downLines, $queryString) {
            $query->when($affiliateID, fn($q) => $q->where('affiliate_id', $affiliateID));
            $query->when(!empty($funnelType), fn($q) => $q->where('funnel_type', $funnelType));
            $query->when($downLines, fn($q) => $q->orWhere('advisor_id', $affiliateID));
            $query->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString));
        });

        $query->whereBetween("created_at", array($startDate, $endDate));
        $query->with('affiliate')->latest();

        return $paginated ? $query->paginate()->withQueryString() : $query->get();
    }

    public function fetchMembers($funnelType, string $startDate, string $endDate, ?string $affiliateUuid = null, ?bool $paginated = true, ?bool $downLines = false, ?string $queryString = null)
    {
        $affiliateID = $affiliateUuid ? $this->userModel::findByUuid($affiliateUuid)?->id : null;

        throw_if($affiliateUuid && !$affiliateID, "Affiliate User not found.");

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

        $query->where(function ($query) use ($affiliateID, $funnelType, $downLines, $queryString) {
            $query->when($affiliateID, fn($q) => $q->where('affiliate_id', $affiliateID));
            $query->when(!empty($funnelType), fn($q) => $q->where('funnel_type', $funnelType));
            $query->when($downLines, fn($q) => $q->orWhere('advisor_id', $affiliateID));
            $query->when(!empty($queryString), fn($q) => $q->whereAnyColumnLike($queryString));
        });

        $query->whereBetween("created_at", array($startDate, $endDate));
        $query->with('affiliate', 'address', 'notificationSettings')->latest();

        return $paginated ? $query->paginate()->withQueryString() : $query->get();
    }

    public function deleteLead(string $uuid)
    {
        $lead = $this->leadModel::ByUUID($uuid)->firstOrFail();
        return $lead->delete();
    }

    private function achievementBadgeOrder()
    {
        $badges = [ENAGIC_ROLE, TRIFECTA_ROLE, ADVISOR_ROLE];
        return sprintf("'%s'", implode("', '", $badges));
    }
}
