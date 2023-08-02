<?php

namespace App\Repositories;

use App\Contracts\Repositories\CalendarRepositoryInterface;
use App\Models\{Calendar, CalendarNotification, Role, User};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CalendarRepository implements CalendarRepositoryInterface
{
    private Model $calenderModel;
    private $roleModel;
    private $calendNotificationModel;

    public function __construct(Model $calender)
    {
        $this->calenderModel = $calender;
        $this->roleModel = app(config('permission.models.role'));
        $this->calendNotificationModel = app(CalendarNotification::class);
    }

    public function calendarByUuid($uuid)
    {
        return $this->calenderModel::ByUUID($uuid)->firstOrFail();
    }

    public function store(array $data)
    {
        $currentUserID = currentUserId();

        $calendar = $this->calenderModel::create($data);
        $roleIds = $this->roleModel::whereUuidIn($data['allowed_audience_roles'])->pluck('id')->toArray();
        $calendar->allowedAudienceRoles()->attach($roleIds);

        $calendar->load(["allowedAudienceRoles", "calendarNotifications" => fn($q) => $q->where('user_id', $currentUserID)]);

        return $calendar;
    }

    public function edit(Calendar $calendar, array $data)
    {
        $currentUserID = currentUserId();

        $calendar->update($data);
        $roleIds = $this->roleModel::whereUuidIn($data['allowed_audience_roles'])->pluck('id')->toArray();
        $calendar->allowedAudienceRoles()->sync($roleIds);

        $calendar->load(["allowedAudienceRoles", "calendarNotifications" => fn($q) => $q->where('user_id', $currentUserID)]);

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
            ->whereHas("allowedAudienceRoles", fn($q) => $q->whereIn('roles.id', $roleIDs))
            ->with(["allowedAudienceRoles", "calendarNotifications" => fn($q) => $q->where('user_id', $user->id)])
            ->when(!empty($startDate), fn($q) => $q->whereBetween('calendar_timestamp', [$startDate, $endDate]))
            ->get();

        return $calendarEvents;
    }

    public function fetchEventsDates(?string $month = null, ?string $year = null)
    {
        $user = currentUser();
        $roleIDs = $this->userRoles($user);

        $startDate = $endDate = null;
        if ($month && $year) {
            $startDate = Carbon::parse("$month $year")->startOfMonth();
            $endDate = Carbon::parse("$month $year")->endOfMonth();
        }


        $calendarEventsDate = $this->calenderModel::query()
            ->selectRaw('date(calendar_timestamp) as date')
            ->whereHas("allowedAudienceRoles", fn($q) => $q->whereIn('roles.id', $roleIDs))
            ->when(($startDate && $endDate), fn($q) => $q->whereBetween('calendar_timestamp', [$startDate, $endDate]))
            ->groupBy('date')
            ->orderBy('date')
            ->get();


        return $calendarEventsDate;
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

    public function calendarNotificationByUuid($uuid)
    {
        return $this->calendNotificationModel::ByUUID($uuid)->firstOrFail();
    }

    public function storeNotification(array $data)
    {
        $currentUserID = currentUserId();
        $calendar = $this->calenderModel::findByUuid($data['calendar_uuid']);

        $data['user_id'] = $currentUserID;
        $calendar->calendarNotifications()->create($data);
        $calendar->load(["allowedAudienceRoles", "calendarNotifications" => fn($q) => $q->where('user_id', $currentUserID)]);

        return $calendar;
    }

    public function editNotification(CalendarNotification $calendarNotification, array $data)
    {
        $calendarNotification->fill($data)->save();
        return $calendarNotification;
    }

    public function deleteCalendarNotification($uuid)
    {
        $calendarNotification = $this->calendarNotificationByUuid($uuid);
        return $calendarNotification->delete();
    }

    public function storeCalenderValidation(array $data)
    {
        return Validator::make($data, [
            "title" => ['required', 'string'],
            "description" => ['required'],
            "link" => ['required'],
            "color" => ['required', Rule::in(self::calenderColors)],
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
            "color" => ['required', Rule::in(self::calenderColors)],
            "display_date" => ['required'],
            "start_time" => ['required'],
            "end_time" => ['required'],
            "allowed_audience_roles" => ['required', 'exists:' . Role::class . ',uuid']
        ]);
    }

    public function storeCalenderNotificationValidation(array $data)
    {
        return Validator::make($data, [
            "calendar_uuid" => ['required', 'string', 'exists:' . Calendar::class . ',uuid'],
            "type" => ['required', Rule::in(self::notificationTypes)],
            "duration" => ['required', 'integer'],
        ]);
    }

    public function editCalenderNotificationValidation(array $data)
    {
        return Validator::make($data, [
            "type" => ['required', Rule::in(self::notificationTypes)],
            "duration" => ['required', 'integer'],
        ]);
    }

}
