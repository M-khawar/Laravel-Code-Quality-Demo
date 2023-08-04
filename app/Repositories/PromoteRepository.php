<?php

namespace App\Repositories;

use App\Contracts\Repositories\PromoteRepositoryInterface;
use App\Traits\CommonServices;
use App\Models\{Lead, PageView, Profile, User};
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PromoteRepository implements PromoteRepositoryInterface
{
    use CommonServices;

    private mixed $profile;

    public function __construct()
    {
        $this->profile = app(Profile::class);
    }

    public function updatePromoteSettings(int $userId, array $data)
    {
        $this->profile::where('user_id', $userId)->update($data);
        return $this->profile::where('user_id', $userId)->firstOrFail();
    }

    public function storeSettingValidation(array $data)
    {
        return Validator::make($data, [
            "display_name" => ["required"],
            "display_text" => ["required"],
            "head_code" => ["nullable"],
            "body_code" => ["nullable"],
        ]);
    }

    public function promoteStats(int $userId, ?string $startDate = null, ?string $endDate = null, ?string $funnelType = null): array
    {
        $views = PageView::distinct('ip')
            ->where(["affiliate_id" => $userId, "funnel_step" => WELCOME_FUNNEL_STEP])
            ->when(!empty($funnelType), fn($q) => $q->where('funnel_type', $funnelType))
            ->whereBetween("created_at", array($startDate, $endDate))
            ->count();

        $leads = Lead::where("affiliate_id", $userId)
            ->whereBetween("created_at", array($startDate, $endDate))
            ->count();

        $members = User::where("affiliate_id", $userId)->excludeAdmins()
            ->whereBetween("created_at", array($startDate, $endDate))
            ->count();

        return [
            "views_count" => $views,
            "leads_count" => $leads,
            "member_count" => $members,
            "opt_in_percentage" => min(100, ($leads / ($views > 0 ? $views : 1)) * 100),
            "member_conv_percentage" => min(100, ($members / ($leads > 0 ? $leads : 1)) * 100),
            "funnel_conv_percentage" => min(100, ($members / ($views > 0 ? $views : 1)) * 100),
        ];
    }

    public function promoteStatsValidation(array $data)
    {
        $periods = $this->availableFilterablePeriods();

        return Validator::make($data, [
            "period" => ["required", Rule::in($periods)],
            "start_date" => ["required_if:period,custom"],
            "end_date" => ["required_if:period,custom"],
        ]);
    }

    private function availableFilterablePeriods(): array
    {
        return [
            "today", "yesterday", "last_seven", "last_fourteen", "this_month", "last_month", "last_three_month",
            "last_six_month", "last_twelve_month", "this_year", "last_year", "custom", "all",
        ];
    }
}
