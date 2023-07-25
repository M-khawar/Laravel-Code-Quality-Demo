<?php

namespace App\Contracts\Repositories;

interface LeadRepositoryInterface
{
    public function storeLead(string $affiliateCode, array $data);

    public function storeLeadValidation(array $data);

    public function storePageVisit(string $affiliateCode, array $data);

    public function storePageVisitValidation(array $data);

    public function fetchLeads(?string $affiliateUuid = null, ?bool $paginated = true, ?bool $downLines = false);
}
