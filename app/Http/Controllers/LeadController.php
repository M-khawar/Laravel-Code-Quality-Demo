<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\LeadRepositoryInterface;
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

    }
}
