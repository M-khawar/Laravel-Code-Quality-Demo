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
            $validated = $this->profileRepository->updateProfileValidation($data)->validated();
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
            $validated = $this->profileRepository->updatePasswordValidation($data)->validated();
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
            dd($request->all());

        } catch (\Exception $exception) {
//            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function updateAdvisorSettings(Request $request)
    {
        try {

            DB::beginTransaction();
            $data = $request->input();
            $validated = $this->profileRepository->updateAdvisorSettingValidation($data)->validated();
            $advisorSettings= $this->profileRepository->updateAdvisorSetting($validated);
            DB::commit();

            $advisorSettings= new SettingResource($advisorSettings);

            return response()->success(__('messages.advisor_setting.updated'), $advisorSettings);


        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }
}
