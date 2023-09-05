<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ProfileRepositoryInterface;
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
            $data = $request->input();
            $user = $this->profileRepository->updateProfile($data);

            return response()->success(__('messages.profile_setting.updated'), $user);

        } catch (\Exception $exception) {
//            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            dd($request->all());

        } catch (\Exception $exception) {
//            DB::rollBack();
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
            dd($request->all());

        } catch (\Exception $exception) {
//            DB::rollBack();
            return $this->handleException($exception);
        }
    }
}
