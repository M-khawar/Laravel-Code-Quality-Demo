<?php

namespace App\Contracts\Repositories;

use App\Models\{Calendar, CalendarNotification};

interface CalendarRepositoryInterface
{
    const calenderColors = ["success", "primary", "info", "danger", "warning", "default"];

    const notificationTypes = [SMS_NOTIF_TYPE, MAIL_NOTIF_TYPE];

    public function calendarByUuid($uuid);

    public function store(array $data);

    public function edit(Calendar $calendar, array $data);

    public function deleteCalendar($uuid);

    public function fetchEvents(?string $date = null);

    public function calendarNotificationByUuid($uuid);

    public function storeNotification(array $data);

    public function editNotification(CalendarNotification $calendarNotification, array $data);

    public function deleteCalendarNotification($uuid);

    public function storeCalenderValidation(array $data);

    public function editCalenderValidation(array $data);

    public function storeCalenderNotificationValidation(array $data);

    public function editCalenderNotificationValidation(array $data);
}
