<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'name' => 'Colten Echave',
            'email' => 'workwithcolten@gmail.com',
            'password' => Hash::make('password'),
            'instagram' => 'colten.echave',
            'phone' => '+1-435-223-4483',
            'affiliate_code' => 'colten95ohhin6',
            'advisor_id' => config('default_settings.default_advisor'),
            'affiliate_id' => config('default_settings.default_advisor'),
            'is_admin' => true,
            'is_advisor' => true,
            'advisor_date' => '2023-06-08',
            'is_active_recruiter' => true,
        ];

        $adminProfile = [
            'display_name' => 'Colten Echave',
            'display_text' => __('messages.default_display_text', locale: 'en'),
            'head_code' => null,
            'body_code' => null,
            'is_enagic' => true,
            'is_trifecta' => true,
            'is_core' => true,
            'enagic_data' => '2023-06-08',
            'trifecta_date' => '2023-06-08',
            'core_date' => '2023-06-08',
            /*  'lead_email' => false,
            'lead_sms' => true,
            'mem_email' => false,
            'mem_sms' => true,
            'promote_watched' => true,
            'welcome_video' => true,
            'fb_group' => true,*/
        ];

        $user = User::create($admin);
        $user->profile()->create($adminProfile);
        event(new Registered($user));

        $this->updateSettings($user);

    }

    private function updateSettings(User $user)
    {
        $user->updateProperty('account_settings', 'lead_email', false);
        $user->updateProperty('account_settings', 'lead_sms', true);
        $user->updateProperty('account_settings', 'mem_email', false);
        $user->updateProperty('account_settings', 'mem_sms', true);

        $user->updateProperty('onboarding', 'welcome_video_watched', false);
        $user->updateProperty('onboarding', 'questionnaire_completed', false);
        $user->updateProperty('onboarding', 'meeting_scheduled', false);
        $user->updateProperty('onboarding', 'joined_facebook_group', false);

        $user->updateProperty('adviser_settings', 'scheduling_link', "https://calendly.com/muhammad-khawar/30min");
        $user->updateProperty('adviser_settings', 'facebook_link', "https://www.facebook.com/colten.shea.echave/");
        $user->updateProperty('adviser_settings', 'advisor_message', "Hey friend, I can't wait to connect with you & give you a roadmap for success!!!");

        $user->updateProperty('promote', 'promote_watched', false);
    }
}
