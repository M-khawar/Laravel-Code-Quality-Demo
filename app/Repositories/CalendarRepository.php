<?php

namespace App\Repositories;

use App\Contracts\Repositories\CalendarRepositoryInterface;
use App\Models\{Calendar, Role, User};
use Carbon\Carbon;
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

    public function fetchEvents(?string $date = null)
    {
        $user = currentUser();
        $roleIDs = $this->userRoles($user);

        $startDate = $endDate = null;
        if ($date) {
            $startDate = Carbon::create($date)->startOfDay();
            $endDate = Carbon::create($date)->endOfDay();
        }

        $calendarEvents = $this->calenderModel::query()
            ->whereHas('allowedAudienceRoles', fn($q) => $q->whereIn('roles.id', $roleIDs))
            ->with('allowedAudienceRoles')
            ->when(!empty($startDate), fn($q) => $q->whereBetween('calendar_timestamp', [$startDate, $endDate]))
            ->get();

        return $calendarEvents;
    }

    protected function userRoles(User $user)
    {
        $user->loadMissing('roles');

        $roleIDs = [];
        if ($user->hasRole(ADMIN_ROLE)) {
            $roleIDs = $this->roleModel::pluck('id')->toArray();
        } else {
            $roleIDs = $user->roles->pluck('id')->toArray();
        }

        return $roleIDs;
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
