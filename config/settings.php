<?php

return [
    //<group>.<setting-property>
    'default' => [
        'onboarding.welcome_video_watched' => false,
        'onboarding.questionnaire_completed' => false,
        'onboarding.meeting_scheduled' => false,
        'onboarding.joined_facebook_group' => false,

        /*** promote settings ***/
        'promote.promote_watched' => false,

        /*** advisor settings ***/
        'adviser_settings.scheduling_link' => '',
        'adviser_settings.facebook_link' => '',
        'adviser_settings.advisor_message' => '',

        /*** account settings ***/
        'account_settings.paypal_account' => '',

        /*** notifications settings ***/
        'notifications.lead_email' => true,
        'notifications.lead_sms' => true,
        'notifications.mem_email' => true,
        'notifications.mem_sms' => true,
        'notifications.event_email' => true,
        'notifications.event_sms' => true,

    ],
];
