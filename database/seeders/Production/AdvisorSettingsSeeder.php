<?php

namespace Database\Seeders\Production;


use App\Models\FunnelStepsCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class AdvisorSettingsSeeder extends ConfigureDatabase
{
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        // $this->truncateMultiple();
        $advisor_settings = $this->getConnection()
        ->table("advisor_settings")
        ->selectRaw("*")
        ->get();
        
            // $advisor_settings = $advisor_setting->take(10);
            // dump($advisor_settings);exit;

            $rawAdvisorSettings = $advisor_settings->map(function ($advisor_setting) {
                return $this->buildAdvisor($advisor_setting);
            });
            // dump($rawAdvisorSettings);exit;
            collect($rawAdvisorSettings)->each(function ($advisor_setting) {
                $this->storeAdvisorSettings($advisor_setting);
            });
            $this->enableForeignKeys();
            // dump($rawFunnels);exit;
    }
    private function buildAdvisor($advisor)
    {
        
        
        return [
         
            "user_id" => $advisor->user_id,
            "scheduling_link" => $advisor->scheduling_link,
            "facebook_link" => $advisor->facebook_link,
            "advisor_message" => $advisor->message
        ];
    }
    private function getUsers($user_id)  {
        $userEmail = $this->getConnection()
                        ->table("users")
                        ->distinct("email")
                        ->where('id',$user_id)
                        ->selectRaw("id,email")->first();

        return $userEmail;
    }

    private function storeAdvisorSettings(array $advisorData)
    {
        // dump($advisorData);die;  
        $userEmail = $this->getUsers($advisorData['user_id']);
        $user = User::firstOrCreate(['email' => $userEmail->email]);
        unset($advisorData['user_id']);
        $user->updateMultipleProperties(ADVISOR_SETTING_GROUP, $advisorData);
            
        } 
        private function updateSettings(User $user)
        {
    
            $properties = ["welcome_video_watched" => false, "questionnaire_completed" => false, "meeting_scheduled" => false, "joined_facebook_group" => false];
            $user->updateMultipleProperties(ONBOARDING_GROUP_ALIAS, $properties);
    
            $properties = [
                "scheduling_link" => "https://calendly.com/muhammad-khawar/30min",
                "facebook_link" => "https://www.facebook.com/colten.shea.echave/",
                "advisor_message" => "Hey friend, I can't wait to connect with you & give you a roadmap for success!!!"
            ];
            $user->updateMultipleProperties(ADVISOR_SETTING_GROUP, $properties);
    
            $user->updateProperty('promote', 'promote_watched', false);
        }


}
