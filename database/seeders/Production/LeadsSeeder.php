<?php

namespace Database\Seeders\Production;

use App\Models\Lead;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class LeadsSeeder extends ConfigureDatabase
{
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["leads"]);
        $leads = $this->getConnection()
        ->table("leads")
        ->distinct("email")
        ->selectRaw("*")
        ->get();
        
            // $leads = $leads->take(10);
            // dump($leads);exit;

            $rawLeads = $leads->map(function ($lead) {
                return $this->buildLead($lead);
            });
            // dump($rawLeads);exit;
            collect($rawLeads)->each(function ($rawlead) {
                $this->storeLead($rawlead);
            });
            $this->enableForeignKeys();
            // dump($rawFunnels);exit;
    }
    private function buildLead($lead)
    {
        // dump($lead);exit;
        $timestamp = (int)$lead->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);
        // dump($created_at);die;

        $leadAffilate = $this->getConnection()
        ->table("users")
        ->distinct("email")
        ->where('id',$lead->affiliate_id)
        ->selectRaw("email")->first();
    // $userN = User::where('email', $userEmail->email)->first();
    $affilate_id = DB::select('SELECT id FROM users WHERE email = :email', ['email' => $leadAffilate->email]);

    if ($affilate_id) {
        $userData['affiliate_id'] = $affilate_id[0]->id;

    } 
    $leadAdvisor = $this->getConnection()
        ->table("users")
        ->distinct("email")
        ->where('id',$lead->affiliate_id)
        ->selectRaw("email")->first();
    // $userN = User::where('email', $userEmail->email)->first();
    $advisor_id = DB::select('SELECT id FROM users WHERE email = :email', ['email' => $leadAdvisor->email]);

    // dump($funnel_step);die;
    if ($advisor_id) {
        $userData['advisor_id'] = $advisor_id[0]->id;

    } 
        return [
            'affiliate_id' => $userData['affiliate_id'],
            'advisor_id' => $userData['advisor_id'],
            'funnel_type' => $lead->funnel_id == 1 ? MASTER_FUNNEL : LIVE_OPPORTUNITY_CALL_FUNNEL,
            'instagram' => $lead->instagram,
            'name'         => $lead->name,
            'status'         => $lead->active == 0 ? LEAD_IN_ACTIVE : LEAD_ACTIVE,
            'email'         => $lead->email,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }

    // private function storeLead(array $leadData)
    // {
    //     $email = $leadData["email"];
    //     unset($leadData["email"]);

    //     Lead::updateOrCreate(
    //         ['email' => $email],
    //         $leadData
    //     );
    // }
    
        private function storeLead(array $leadData)
        {
            $validator = Validator::make(['email' => $leadData['email']], ['email' => 'email']);

            if ($validator->fails()) {
                
                return;
            }

            $email = $leadData["email"];
            unset($leadData["email"]);

            Lead::updateOrCreate(
                ['email' => $email],
                $leadData
            );
        }
}
