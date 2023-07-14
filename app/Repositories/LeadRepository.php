<?php

namespace App\Repositories;

use App\Contracts\Repositories\LeadRepositoryInterface;
use App\Models\User;
use App\Packages\SendGridWrapper\SendGridInitializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class LeadRepository implements LeadRepositoryInterface
{
    use SendGridInitializer;

    private Model $leadModel;

    public function __construct(Model $leadModel)
    {
        $this->leadModel = $leadModel;
    }

    public function storeLead(string $affiliateCode, array $data)
    {
        $affiliate = User::query()
            ->when(!empty($affiliateCode), fn($q) => $q->whereAffiliate($affiliateCode))
            ->when(empty($affiliateCode), fn($q) => $q->whereDefaultAdvisor())
            ->first();

        $data = array_merge($data, [
            'advisor_id' => $affiliate->is_advisor ? $affiliate->id : $affiliate->advisor_id,
            'affiliate_id' => $affiliate->id,
        ]);

        $lead = $this->leadModel::firstOrCreate(
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

        return $lead;
    }

    public function storeLeadValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'instagram' => ['nullable'],
            'affiliate_code' => ['nullable', 'exists:' . User::class . ',affiliate_code'],
        ]);
    }
}
