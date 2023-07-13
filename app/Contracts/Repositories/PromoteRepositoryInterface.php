<?php

namespace App\Contracts\Repositories;

interface PromoteRepositoryInterface
{
    public function updatePromoteSettings(int $userId, array $data);

    public function storeSettingValidation(array $data);

}
