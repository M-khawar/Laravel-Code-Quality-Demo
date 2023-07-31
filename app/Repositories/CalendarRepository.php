<?php

namespace App\Repositories;

use App\Contracts\Repositories\CalendarRepositoryInterface;
use App\Models\{Calendar, Role};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CalendarRepository implements CalendarRepositoryInterface
{
    private Model $calenderModel;
    private $roleModel;

    public function __construct(Model $calender)
    {
        $this->calenderModel = $calender;
        $this->roleModel = app(config('permission.models.role'));
    }

    public function calendarByUuid($uuid)
    {
        return $this->calenderModel::ByUUID($uuid)->firstOrFail();
    }

    public function store(array $data)
    {
        $calendar = $this->calenderModel::create($data);
        $roleIds = $this->roleModel::whereUuidIn($data['allowed_audience_roles'])->pluck('id')->toArray();
        $calendar->allowedAudienceRoles()->attach($roleIds);
        $calendar->load("allowedAudienceRoles");
        return $calendar;
    }

    public function edit(Calendar $calendar, array $data)
    {
        $calendar->update($data);
        $roleIds = $this->roleModel::whereUuidIn($data['allowed_audience_roles'])->pluck('id')->toArray();
        $calendar->allowedAudienceRoles()->sync($roleIds);
        $calendar->load("allowedAudienceRoles");
        return $calendar;
    }

    public function deleteCalendar($uuid)
    {
        $calendar = $this->calendarByUuid($uuid);
        $calendar->allowedAudienceRoles()->detach();
        return $calendar->delete();
    }

    public function storeCalenderValidation(array $data)
    {
        return Validator::make($data, [
            "title" => ['required', 'string'],
            "description" => ['required'],
            "link" => ['required'],
            "color" => ['required', Rule::in($this->calenderColors())],
            "display_date" => ['required'],
            "start_time" => ['required'],
            "end_time" => ['required'],
            "allowed_audience_roles" => ['required', 'exists:' . Role::class . ',uuid']
        ]);
    }

    public function editCalenderValidation(array $data)
    {
        return Validator::make($data, [
            "title" => ['required', 'string'],
            "description" => ['required'],
            "link" => ['required'],
            "color" => ['required', Rule::in($this->calenderColors())],
            "display_date" => ['required'],
            "start_time" => ['required'],
            "end_time" => ['required'],
            "allowed_audience_roles" => ['required', 'exists:' . Role::class . ',uuid']
        ]);
    }

    public function calenderColors(): array
    {
        return [
            "success", "primary", "info", "danger", "warning", "default",
        ];
    }


}
