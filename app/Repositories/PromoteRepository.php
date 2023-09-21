<?php

namespace App\Repositories;

use App\Contracts\Repositories\PromoteRepositoryInterface;
use App\Traits\CommonServices;
use App\Traits\StatsDelegates;
use App\Models\{Profile, Setting};
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PromoteRepository implements PromoteRepositoryInterface
{
    use CommonServices, StatsDelegates;

    const LIVE_TIMEZONES = ["PST", "MST", "CST", "EST"];

    private mixed $profile;
    private mixed $settingsModel;

    public function __construct()
    {
        $this->profile = app(Profile::class);
        $this->settingsModel = app(Setting::class);
    }

    public function updatePromoteSettings(array $data)
    {
        $user = currentUser();
        $userId = $user->id;

        $adminSettings = isset($data["admin_settings"]) ? $data["admin_settings"] : null;
        if ($adminSettings) unset($data["admin_settings"]);

        $this->profile::where('user_id', $userId)->update($data);
        $promoteSettings = $this->profile::where('user_id', $userId)->firstOrFail();

        if ($user->hasRole(ADMIN_ROLE)) {
            $adminSettingsResponse = $this->updateAdminSettings($user, $adminSettings);
            $promoteSettings->setRelation("adminSettings", $adminSettingsResponse);
        }

        return $promoteSettings;
    }

    private function updateAdminSettings($user, $adminSettings)
    {
        if (!$user->hasRole(ADMIN_ROLE)) {
            return;
        }

        list(
            "youtube_live" => $youtubeLive, "chatroll" => $chatroll, "blocked_words" => $blockedWords,
            "is_live" => $isLive, "hide_live_content" => $hideLiveContent, "live_at" => $liveAt,
            "live_timezone" => $liveTimezone,) = $adminSettings;

        $adminSettingsDict = [
            YOUTUBE_LIVE_SETTING => @$youtubeLive ?? "",
            CHATROLL_SETTING => @$chatroll ?? "",
            BLOCKED_WORDS_SETTING => @$blockedWords ?? "",
            IS_LIVE_SETTING => @$isLive ?? config("settings.admin.is_live"),
            HIDE_LIVE_CONTENT_SETTING => @$hideLiveContent ?? config("settings.admin.hide_live_content"),
            LIVE_AT_SETTING => @$liveAt ?? "",
            LIVE_TIMEZONE_SETTING => @$liveTimezone ?? "",
        ];

        $this->settingsModel::updateMultipleProperties(ADMIN_SETTINGS_GROUP, $adminSettingsDict);

        return $this->settingsModel::settingFilters(group: ADMIN_SETTINGS_GROUP)->get();
    }

    public function storeSettingValidation(array $data)
    {
        $user = currentUser();

        $roles = [
            "display_name" => ["required"],
            "display_text" => ["required"],
            "head_code" => ["nullable"],
            "body_code" => ["nullable"],
        ];

        if ($user->hasRole(ADMIN_ROLE)) {
            $roles = array_merge($roles, [
                "admin_settings.youtube_live" => ["nullable", "string"],
                "admin_settings.chatroll" => ["nullable"],
                "admin_settings.blocked_words" => ["nullable", ""],
                "admin_settings.is_live" => ["nullable", "boolean"],
                "admin_settings.hide_live_content" => ["nullable", "boolean"],
                "admin_settings.live_at" => ["nullable"],
                "admin_settings.live_timezone" => ["nullable", "in:" . implode(',', self::LIVE_TIMEZONES) . ""],
            ]);
        }

        return Validator::make($data, $roles);
    }

    public function promoteStats(int $userId, string $startDate = null, string $endDate = null, ?string $funnelType = null): array
    {
        $views = $this->pageViewsCount($startDate, $endDate, affiliateId: $userId, funnelType: $funnelType);
        $leads = $this->leadsCount($startDate, $endDate, affiliateId: $userId, funnelType: $funnelType);
        $members = $this->membersCount($startDate, $endDate, affiliateId: $userId, funnelType: $funnelType);

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
