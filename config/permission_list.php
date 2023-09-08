<?php


return [
    "permissions" => array(
        ["name" => "access_level.admin"],
        ["name" => "access_level.enagic"],
        ["name" => "access_level.trifecta"],
        ["name" => "access_level.advisor"],
        ["name" => "access_level.core"],
        ["name" => "access_level.active_recruiter"],

        ["name" => "course"],  // user.subscription || user.is_advisor || user.questionnaire_complete===true || user.is_enagic ||
        ["name" => "leaderboard"],  //user.is_advisor || (user.subscription && user.is_enagic))
        ["name" => "promote"],  //user.is_advisor || (user.subscription && user.is_enagic))
        ["name" => "questionnaire.watch_answers"],  //advisor

        ["name" => "member.update_role"],  //advisor,  (user.is_advisor && member.advisor_id==user.id)
        ["name" => "member.member_info.view"],  //advisor
        ["name" => "member.affiliate_info.view"],  //advisor

        ["name" => "admin.dashboard"],  //admin
        ["name" => "admin.admin_course"],  //admin
        ["name" => "admin.update_member_administration"],  //admin

        ["name" => "settings.advisor_settings"],  //advisor
        ["name" => "settings.notifications.lead"],  //advisor, enagic
        ["name" => "settings.notifications.member"],  //advisor, enagic
        ["name" => "settings.notifications.calendar_event"],  //all roles except `All member`

        ["name" => "notes.create"],  //advisor
        ["name" => "notes.edit"],  //advisor

        ["name" => "calendar.notifications.view"], //admin
        ["name" => "calendar.notifications.create"], //admin
        ["name" => "calendar.notifications.edit"], //admin
        ["name" => "calendar.notifications.delete"], //admin

        ["name" => "calendar.events.view"], //all roles except `All member`
        ["name" => "calendar.events.create"], //admin
        ["name" => "calendar.events.edit"], //admin
        ["name" => "calendar.events.delete"], //admin

        ["name" => "promote.blocked_words_operations"], //admin
        ["name" => "promote.live_at_operations"], //admin
        ["name" => "promote.downlines"], //advisor

        ["name" => "quick_links.pro_facebook_group"], //enagic
        ["name" => "quick_links.r2f_members"], //enagic
        ["name" => "quick_links.r2f_telegram"], //enagic
        ["name" => "quick_links.software_guide"], //enagic
        ["name" => "quick_links.advisor_telegram"], //advisor

    ),

    "admin_permissions" => array(
        PERMISSION_LEVEL_ADMIN,
        PERMISSION_ADMIN_DASHBOARD,
        PERMISSION_ADMIN_COURSE,
        PERMISSION_UPDATE_MEMBER_ADMINISTRATION,
        PERMISSION_CALENDAR_NOTIFICATIONS_VIEW,
        PERMISSION_CALENDAR_NOTIFICATIONS_CREATE,
        PERMISSION_CALENDAR_NOTIFICATIONS_EDIT,
        PERMISSION_CALENDAR_NOTIFICATIONS_DELETE,
        PERMISSION_CALENDAR_EVENTS_CREATE,
        PERMISSION_CALENDAR_EVENTS_EDIT,
        PERMISSION_CALENDAR_EVENTS_DELETE,
        PERMISSION_BLOCKEDWORDS_OPERATIONS,
        PERMISSION_LIVEAT_OPERATIONS,
    ),

    "enagic_permissions" => array(
        PERMISSION_LEVEL_ENAGIC,
        PERMISSION_COURSE,
        PERMISSION_LEADERBOARD,
        PERMISSION_PROMOTE,
        PERMISSION_SETTINGS_LEAD_NOTIFICATION,
        PERMISSION_SETTINGS_MEMBER_NOTIFICATION,
        PERMISSION_QUICKLINKS_PRO_FB_GROUP,
        PERMISSION_QUICKLINKS_R2F_MEMBERS,
        PERMISSION_QUICKLINKS_R2F_TELEGRAM,
        PERMISSION_QUICKLINKS_SOFTWARE_GUID,
    ),

    "advisor_permissions" => array(
        PERMISSION_LEVEL_ADVISOR,
        PERMISSION_COURSE,
        PERMISSION_LEADERBOARD,
        PERMISSION_PROMOTE,
        PERMISSION_QUESTIONNAIRE_WATCH_ANSWER,
        PERMISSION_MEMBER_UPDATE_ROLE,
        PERMISSION_MEMBER_INFO_VIEW,
        PERMISSION_MEMBER_AFFILIATE_INFO_VIEW,
        PERMISSION_ADVISOR_SETTINGS,
        PERMISSION_SETTINGS_LEAD_NOTIFICATION,
        PERMISSION_SETTINGS_MEMBER_NOTIFICATION,
        PERMISSION_NOTES_CREATE,
        PERMISSION_NOTES_EDIT,
        PERMISSION_PROMOTE_DOWNLINES,
        PERMISSION_QUICKLINKS_ADVISOR_TELEGRAM,
    ),

    "all_members" => array(
        PERMISSION_SETTINGS_CALENDAR_EVENT_NOTIFICATION,
        PERMISSION_CALENDAR_EVENTS_VIEW,
    ),
];
