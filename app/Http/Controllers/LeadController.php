<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Http\Resources\{LeadCollection, MemberCollection};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{

    public function __construct(private LeadRepositoryInterface $leadRepository)
    {
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->leadRepository->storeLeadValidation($request->input())->validate();

            $affiliateCode = $request->affiliate_code;
            $data = $request->except('affiliate_code');

            $lead = $this->leadRepository->storeLead($affiliateCode, $data);

            DB::commit();

            //generate email & sms depending on settings

            $message = $lead->wasRecentlyCreated ? __('messages.lead.created') : __('messages.lead.existed');
            return response()->message($message);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function storeVisits(Request $request)
    {
        try {
            DB::beginTransaction();

            $affiliateCode = $request->affiliate_code;
            $headers = ['ip' => $request->ip(), 'user_agent' => $request->header('user_agent')];
            $data = array_merge($request->except('affiliate_code'), $headers);

            $this->leadRepository->storePageVisitValidation($data)->validate();
            $this->leadRepository->storePageVisit($affiliateCode, $data);

            DB::commit();

            return response()->message(__('messages.visit.logged'));

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function getLead(Request $request, string $uuid = null)
    {
        try {
            $paginated = $request->boolean('paginated');
            $downLines = $request->boolean('downlines');
            $period = $request->period ?? "all";
            $funnelType = @$request->funnel_type;
            $queryString = $request->input('query');

            $filterRange = [
                "start_date" => @$request->start_date,
                "end_date" => @$request->end_date,
            ];

            $dateRange = $this->leadRepository->periodConversion($period, $filterRange);
            $leads = $this->leadRepository->fetchLeads($funnelType, $dateRange->start_date, $dateRange->end_date, $uuid, $paginated, $downLines, $queryString);
            $leads = (new LeadCollection($leads))->response()->getData(true);

            return response()->success(__("messages.lead.fetched"), $leads);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getMembers(Request $request, string $uuid = null)
    {
        try {
            $paginated = $request->boolean('paginated');
            $downLines = $request->boolean('downlines');
            $period = $request->period ?? "all";
            $funnelType = @$request->funnel_type;
            $queryString = $request->input('query');

            $filterRange = [
                "start_date" => @$request->start_date,
                "end_date" => @$request->end_date,
            ];

            $dateRange = $this->leadRepository->periodConversion($period, $filterRange);
            $members = $this->leadRepository->fetchMembers($funnelType, $dateRange->start_date, $dateRange->end_date, $uuid, $paginated, $downLines, $queryString);
            $members = (new MemberCollection($members))->response()->getData(true);

            return response()->success(__("messages.members.fetched"), $members);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroyLead($uuid)
    {
        try {
            DB::beginTransaction();
            $this->leadRepository->deleteLead($uuid);
            DB::commit();

            return response()->message(__("messages.lead.deleted"));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

}
