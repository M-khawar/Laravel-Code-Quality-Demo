<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ProfileRepositoryInterface;
use App\Http\Resources\SettingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct(public ProfileRepositoryInterface $profileRepository)
    {
    }

    public function updateUserInfo(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->input();
            $validated = $this->profileRepository->updateProfileValidation($data)->validate();
            $user = $this->profileRepository->updateProfile($validated);
            DB::commit();

            return response()->success(__('messages.profile_setting.updated'), $user);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->input();
            $validated = $this->profileRepository->updatePasswordValidation($data)->validate();
            $this->profileRepository->updatePassword($validated);
            DB::commit();

            return response()->message(__('messages.password.updated'));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function updateNotifications(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->input();
            $validated = $this->profileRepository->updateNotificationSettingValidation($data)->validate();
            $notificationSettings = $this->profileRepository->updateNotificationSetting($validated);
            DB::commit();

            return response()->success(__('messages.notification_setting.updated'), $notificationSettings);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function updateAdvisorSettings(Request $request)
    {
        try {

            DB::beginTransaction();
            $data = $request->input();
            $validated = $this->profileRepository->updateAdvisorSettingValidation($data)->validate();
            $advisorSettings = $this->profileRepository->updateAdvisorSetting($validated);
            DB::commit();

            $advisorSettings = new SettingResource($advisorSettings);

            return response()->success(__('messages.advisor_setting.updated'), $advisorSettings);


        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }
}
