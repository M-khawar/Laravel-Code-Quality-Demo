<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->storeLeadValidation($request->input())->validate();

            $affiliateCode = $request->affiliate_code;
            $data = $request->except('affiliate_code');

            $affiliate = User::query()
                ->when($affiliateCode, fn($q) => $q->whereAffiliate($affiliateCode))
                ->when(empty($affiliateCode), fn($q) => $q->whereDefaultAdvisor())
                ->first();


            $data = array_merge($data, [
                'advisor_id' => $affiliate->is_advisor ? $affiliate->id : $affiliate->advisor_id,
                'affiliate_id' => $affiliate->id,
            ]);

            $lead= Lead::firstOrCreate(
                ['email' => $data['email'], 'advisor_id' => $data['advisor_id']],
                $data
            );

            if ($lead->wasRecentlyCreated){
                // save lead to sendgrid
            }

            DB::commit();


            //splitup data
            // find affiliate if not then set admin as default

            //create lead using firstOrCreate()
            // if recently_created then store data to sendgrid
            //set advisor
            //generate email & sms depending on settings

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    protected function storeLeadValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'instagram' => ['nullable'],
            'affiliate_code' => ['nullable', 'exists:' . User::class . ',affiliate_code'],
        ]);
    }
}
