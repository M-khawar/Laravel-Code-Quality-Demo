<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\PromoteRepositoryInterface;
use App\Http\Resources\PromoteSettingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoteController extends Controller
{
    public function __construct(public PromoteRepositoryInterface $promoteRepository)
    {
    }

    public function settings(Request $request)
    {
        try {
            DB::beginTransaction();

            $userId = currentUserId();
            $data = $request->input();

            $this->promoteRepository->storeSettingValidation($data)->validate();
            $userProfile = $this->promoteRepository->updatePromoteSettings($userId, $data);

            DB::commit();

            $data = new PromoteSettingResource($userProfile);
            return response()->success("Successfully, Promote settings updated.", $data);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
