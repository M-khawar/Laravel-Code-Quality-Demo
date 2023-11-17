<?php

namespace Database\Seeders\Production;


use App\Models\FunnelStepsCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class FunnelSeeder extends ConfigureDatabase
{
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["funnel_steps_codes"]);
        $funnels = $this->getConnection()
        ->table("funnel_steps_codes")
        ->join("funnel_steps", "funnel_steps_codes.step_id", "=", "funnel_steps.id")
        ->selectRaw("funnel_steps_codes.*, funnel_steps.funnel_id")
        ->get();
        
            // $funnels = $funnels->take(10);
            // dump($funnels);exit;

            $rawFunnels = $funnels->map(function ($funnel) {
                return $this->buildFunnel($funnel);
            });
            collect($rawFunnels)->each(function ($funnel) {
                $this->storeFunnelStepCode($funnel);
            });
            $this->enableForeignKeys();
            // dump($rawFunnels);exit;
    }
    private function buildFunnel($funnel)
    {
        // dump($funnel);exit;
       
        $funnel_step = $this->getFunnelStep($funnel->funnel_id, $funnel->step_id);
       
        return [
            'user_id' => $funnel->user_id,
            'funnel_type' => $funnel->funnel_id == 1 ? MASTER_FUNNEL : LIVE_OPPORTUNITY_CALL_FUNNEL,
            'funnel_step' => $funnel_step,
            'code' => $funnel->code,
            'created_at' => $funnel->createdAt,
            'updated_at' => $funnel->updatedAt,
        ];
    }

    private function getFunnelStep($funnel_id, $step_id)
    {
        switch ($funnel_id) {
            case 1:
                switch ($step_id) {
                    case 1:
                        return WELCOME_FUNNEL_STEP;
                    case 2:
                        return WEBINAR_FUNNEL_STEP;
                    case 3:
                        return CHECKOUT_FUNNEL_STEP;
                    case 4:
                        return THANKYOU_FUNNEL_STEP;
                }
                break;

            case 2:
                switch ($step_id) {
                    case 5:
                        return WELCOME_FUNNEL_STEP;
                    case 6:
                        return WEBINAR_FUNNEL_STEP;
                    case 7:
                        return CHECKOUT_FUNNEL_STEP;
                    case 8:
                        return THANKYOU_FUNNEL_STEP;
                }
                break;

        }

        return ''; 
    }
    private function storeFunnelStepCode(array $userData)
    {
        // dump($userData['user_id']);die;  
        $userEmail = $this->getConnection()
            ->table("users")
            ->distinct("email")
            ->where('id',$userData['user_id'])
            ->selectRaw("email")->first();

        // $userN = User::where('email', $userEmail->email)->first();
        $userN = DB::select('SELECT id FROM users WHERE email = :email', ['email' => $userEmail->email]);

        // dump($userN[0]->id);die;
        if ($userN) {
            $userData['user_id'] = $userN[0]->id;

            $funnel = FunnelStepsCode::firstOrCreate($userData);
        } 
    }


}
