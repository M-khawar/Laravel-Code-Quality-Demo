<?php

namespace App\Contracts\Repositories;

interface LeadRepositoryInterface
{
    public function storeLead(string $affiliateCode, array $data);

    public function storeLeadValidation(array $data);

}
