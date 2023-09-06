<?php

namespace App\Contracts\Repositories;

interface ProfileRepositoryInterface
{

    public function updateProfile(array $data);

    public function updateProfileValidation(array $data);

    public function updatePassword(array $data);

    public function updatePasswordValidation(array $data);

}
