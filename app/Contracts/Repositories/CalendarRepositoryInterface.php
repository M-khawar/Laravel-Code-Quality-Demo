<?php

namespace App\Contracts\Repositories;

use App\Models\Calendar;

interface CalendarRepositoryInterface
{

    public function calendarByUuid($uuid);

    public function store(array $data);

    public function edit(Calendar $calendar, array $data);

    public function deleteCalendar($uuid);

    public function fetchEvents(?string $date = null);

    public function storeCalenderValidation(array $data);

    public function editCalenderValidation(array $data);

    public function calenderColors(): array;

}
