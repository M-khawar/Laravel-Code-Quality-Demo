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

            $data = $request->input();

            $this->promoteRepository->storeSettingValidation($data)->validate();
            $promotSettings = $this->promoteRepository->updatePromoteSettings($data);

            DB::commit();

            $data = new PromoteSettingResource($promotSettings);
            return response()->success("Successfully, Promote settings updated.", $data);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $data= $request->input();
            $this->promoteRepository->promoteStatsValidation($data)->validate();

            $userId = currentUserId();
            $period = $request->period ?? "all";
            $funnelType = @$request->funnel_type;

            $filterRange = [
                "start_date" => @$request->start_date,
                "end_date" => @$request->end_date,
            ];

            $dateRange = $this->promoteRepository->periodConversion($period, $filterRange);
            $stats = $this->promoteRepository->promoteStats($userId, $dateRange->start_date, $dateRange->end_date, $funnelType);

            return response()->success("Successfully Stats Fetched.", $stats);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
