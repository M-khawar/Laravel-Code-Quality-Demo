<?php

namespace App\Repositories;

use App\Contracts\Repositories\PromoteRepositoryInterface;
use App\Traits\CommonServices;
use App\Traits\StatsDelegates;
use App\Models\{Profile};
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PromoteRepository implements PromoteRepositoryInterface
{
    use CommonServices, StatsDelegates;

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

    public function promoteStats(int $userId, string $startDate = null, string $endDate = null, ?string $funnelType = null): array
    {
        $views = $this->pageViewsCount($startDate, $endDate, $userId);
        $leads = $this->leadsCount($startDate, $endDate, $userId);
        $members = $this->membersCount($startDate, $endDate, $userId);

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
}
