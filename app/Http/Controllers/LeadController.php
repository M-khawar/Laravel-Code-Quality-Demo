<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Packages\SendGridWrapper\SendGridInitializer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    use SendGridInitializer;

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

            $lead = Lead::firstOrCreate(
                ['email' => $data['email'], 'advisor_id' => $data['advisor_id']],
                $data
            );

            if ($lead->wasRecentlyCreated) {
                $listID = config('default_settings.sendgrid_masterclass_list');
                $nameArr = explode(" ", $lead->name);
                $data = [
                    "first_name" => @$nameArr[0],
                    "last_name" => @$nameArr[1],
                    "email" => $lead->email,
                    "custom_fields" => [
                        "w1_T" => $affiliate?->affiliate_code
                    ],
                ];

                $this->sendgridContactManager()->addContact($listID, $data);
            }

            DB::commit();

            //generate email & sms depending on settings

            $message = $lead->wasRecentlyCreated ? __('messages.lead.created') : __('messages.lead.existed');
            return response()->message($message);

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
