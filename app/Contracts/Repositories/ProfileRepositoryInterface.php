<?php

namespace App\Contracts\Repositories;

interface ProfileRepositoryInterface
{

    public function updateProfile(array $data);

    public function updateProfileValidation(array $data);

}
