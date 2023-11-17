<?php

namespace Database\Seeders\Production;


use App\Models\PageView;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class PageViewsSeeder extends ConfigureDatabase
{
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["page_views"]);
        $pageViews = $this->getConnection()
        ->table("page_views")
        ->selectRaw("page_views.*")
        ->get();
        
            // $pageViews = $pageViews->take(10);
            // dump($pageViews);exit;

            $rawpageViews = $pageViews->map(function ($pageView) {
                return $this->buildFunnel($pageView);
            });
            collect($rawpageViews)->each(function ($rawpageView) {
                $this->storepageView($rawpageView);
            });
            $this->enableForeignKeys();
            // dump($rawFunnels);exit;
    }
    private function buildFunnel($pageView)
    {
        $timestamp = (int)$pageView->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);
        // dump($created_at);die;
        $funnel_step = $this->getpageViewStep($pageView->funnel_id, $pageView->step_id);

        $userEmail = $this->getConnection()
        ->table("users")
        ->distinct("email")
        ->where('id',$pageView->affiliate_id)
        ->selectRaw("email")->first();
    // $userN = User::where('email', $userEmail->email)->first();
    $userN = DB::select('SELECT id,email FROM users WHERE email = :email', ['email' => $userEmail->email]);

    // dump($funnel_step);die;
    if ($userN) {
        $userData['user_id'] = $userN[0]->id;

    } 
        return [
            'affiliate_id' => $userData['user_id'],
            'funnel_type' => $pageView->funnel_id == 1 ? MASTER_FUNNEL : LIVE_OPPORTUNITY_CALL_FUNNEL,
            'funnel_step' => $funnel_step,
            'user_agent' => $pageView->user_agent,
            'ip'         => $pageView->ip,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }

    private function getpageViewStep($funnel_id, $step_id)
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
    private function storePageView(array $userData)
    {
        
            $PageView = PageView::firstOrCreate($userData);
    }


}
