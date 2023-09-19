<?php

namespace App\Traits;

use App\Models\{Lead, PageView, User};

trait StatsDelegates
{

    private function availableFilterablePeriods(): array
    {
        return [
            "today", "yesterday", "last_seven", "last_fourteen", "this_month", "last_month", "last_three_month",
            "last_six_month", "last_twelve_month", "this_year", "last_year", "custom", "all",
        ];
    }

    protected function pageViewsCount($startDate, $endDate, ?int $affiliateId = null, ?string $funnelType = null): int
    {
        return PageView::distinct('ip')
            ->where("funnel_step", WELCOME_FUNNEL_STEP)
            ->when(!empty($affiliateId), fn($q) => $q->where("affiliate_id", $affiliateId))
            ->when(!empty($funnelType) && $funnelType !== "all", fn($q) => $q->where('funnel_type', $funnelType))
            ->whereBetween("created_at", array($startDate, $endDate))
            ->count();
    }

    protected function leadsCount($startDate, $endDate, ?int $affiliateId = null, ?string $funnelType = null): int
    {
        return Lead::query()
            ->when(!empty($affiliateId), fn($q) => $q->where("affiliate_id", $affiliateId))
            ->whereBetween("created_at", array($startDate, $endDate))
            ->when(!empty($funnelType) && $funnelType !== "all", fn($q) => $q->where('funnel_type', $funnelType))
            ->count();
    }

    protected function membersCount($startDate, $endDate, ?int $affiliateId = null, ?string $funnelType = null): int
    {
        return User::query()
            ->excludeAdmins()
            ->when(!empty($affiliateId), fn($q) => $q->where("affiliate_id", $affiliateId))
            ->whereBetween("created_at", array($startDate, $endDate))
            ->when(!empty($funnelType) && $funnelType !== "all", fn($q) => $q->where('funnel_type', $funnelType))
            ->count();
    }

    protected function enagicCount($startDate, $endDate)
    {
        $usersCount = User::whereHas("roles", function ($q) use ($startDate, $endDate) {
            $q->whereIn("roles.name", [ENAGIC_ROLE, TRIFECTA_ROLE, ADVISOR_ROLE])
                ->whereBetween(config('permission.table_names.model_has_roles') . ".created_at", array($startDate, $endDate));
        })->count();

        return $usersCount;
    }

    protected function coreCount($startDate, $endDate)
    {
        $roleModel = app(config("permission.models.role"));
        $rolePivotTable = config("permission.table_names.model_has_roles");

        $roles = $roleModel::where("name", CORE_ROLE)
            ->withCount(["users" => fn($q) => $q->whereBetween("$rolePivotTable.created_at", array($startDate, $endDate))])
            ->first();

        return $roles->users_count;
    }

    protected function trifectaCount($startDate, $endDate)
    {
        $roleModel = app(config("permission.models.role"));
        $rolePivotTable = config("permission.table_names.model_has_roles");

        $roles = $roleModel::where("name", TRIFECTA_ROLE)
            ->withCount(["users" => fn($q) => $q->whereBetween("$rolePivotTable.created_at", array($startDate, $endDate))])
            ->first();

        return $roles->users_count;
    }

    protected function advisorCount($startDate, $endDate)
    {
        $roleModel = app(config("permission.models.role"));
        $rolePivotTable = config("permission.table_names.model_has_roles");

        $roles = $roleModel::where("name", ADVISOR_ROLE)
            ->withCount(["users" => fn($q) => $q->whereBetween("$rolePivotTable.created_at", array($startDate, $endDate))])
            ->first();

        return $roles->users_count;
    }
}
