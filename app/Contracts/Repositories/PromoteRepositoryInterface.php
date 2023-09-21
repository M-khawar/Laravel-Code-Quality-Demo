<?php

namespace App\Contracts\Repositories;

interface PromoteRepositoryInterface
{
    public function updatePromoteSettings(array $data);

    public function storeSettingValidation(array $data);

    public function periodConversion(string $period, ?array $data = []): object;

    public function promoteStats(int $userId, string $startDate, string $endDate, ?string $funnelType = null): array;

    public function promoteStatsValidation(array $data);
}
