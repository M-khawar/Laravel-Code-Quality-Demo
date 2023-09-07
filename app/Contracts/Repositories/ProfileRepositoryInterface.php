<?php

namespace App\Contracts\Repositories;

interface ProfileRepositoryInterface
{

    public function updateProfile(array $data);

    public function updateProfileValidation(array $data);

    public function updatePassword(array $data);

    public function updatePasswordValidation(array $data);

    public function updateAdvisorSetting(array $data);

    public function updateAdvisorSettingValidation(array $data);

    public function updateNotificationSetting(array $data);

    public function updateNotificationSettingValidation(array $data);

}
