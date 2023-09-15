<?php

namespace App\Contracts\Repositories;

interface LeadRepositoryInterface
{
    public function storeLead(string $affiliateCode, array $data);

    public function storeLeadValidation(array $data);

    public function storePageVisit(string $affiliateCode, array $data);

    public function storePageVisitValidation(array $data);

    public function fetchLeads(
        $funnelType, string $startDate,
        string $endDate,
        ?string $affiliateUuid = null,
        ?bool $paginated = true,
        ?bool $downLines = false,
        ?string $queryString = null
    );

    public function fetchMembers(
        $funnelType,
        string $startDate,
        string $endDate,
        string $affiliateUuid=null,
        ?bool $paginated = true,
        ?bool $downLines = false,
        ?string $queryString = null,
        ?string $filterableRole = null
    );

    public function deleteLead(string $uuid);

    public function periodConversion(string $period, ?array $data = []): object;
}
