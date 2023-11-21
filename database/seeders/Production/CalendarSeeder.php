<?php

namespace Database\Seeders\Production;

use App\Models\Calendar;
use App\Models\Course;
use App\Models\Media;
use App\Models\Role;
use App\Models\Video;
use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class CalendarSeeder extends ConfigureDatabase
{
    // private $roleModel;
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["calendars","has_calendar_permissions"]);

        $calendars = $this->getConnection()
                        ->table("calendars")
                        ->selectRaw("*")
                        ->get();

    //    $calendars = $calendars->take(10);
        $rawcalendars = $calendars->map(function ($calendar) {
            return $this->buildCourse($calendar);
        });
        // dump($rawcalendars);exit;
        collect($rawcalendars)->each(function ($calendar) {
            $this->storeCalendar($calendar);
        });

        $this->enableForeignKeys();
    }

    private function storeCalendar(array $calendarData)
    {
        $roles = $calendarData["roles"];
        $colors = $calendarData["color"];
        $calendarId = $calendarData["id"];
        unset($calendarData["color"]);
        unset($calendarData["id"]);
        $calendarData["color"] = $this->mapColor($colors);
        unset($calendarData["roles"]);
        $calendar = Calendar::firstOrCreate([...$calendarData]);
        foreach ($roles as $role) {
            $roleId = Role::where('name', $role)->value('id');
            // dump($roleId);exit;
            $calendar->allowedAudienceRoles()->attach($roleId);
        }// todo
        // Store calendar notifications
         $calendarNotifications = $this->getConnection()
         ->table("calendar_notification")
         ->where('calendar_id', 257)
         ->selectRaw("*")
         ->get();
         
        $rawCalendarNotification = $calendarNotifications->map(function ($calendarNotification) use ($calendar) {
            return $this->buildCalendarNotification($calendarNotification);
        });
        collect($rawCalendarNotification)->each(function ($calendarNotification) use ($calendar) {
           
            $calendar->calendarNotifications()->create($calendarNotification);
            
            });
        // dump($rawCalendarNotification);exit;

    }
    private function buildCalendarNotification($calendar)
    {
        $timestamp = (int)$calendar->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);
        $userID =  $this->getUsers($calendar);
        // dump($userID);die;
        return [
            'type' => $calendar->type,
            'duration' => $calendar->duration,
            'duration_type' => $calendar->duration_type,
            'sent_status' => $calendar->sent_status,
            'user_id' => $userID,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            // 'course_id' => $course_Id  //logic
           
        ];
    }
    private function getUsers($calendar)  {
        $userEmail = $this->getConnection()
                        ->table("users")
                        ->distinct("email")
                        ->where('id',$calendar->user_id)
                        ->selectRaw("email")->first();

        // $userN = User::where('email', $userEmail->email)->first();
        $userN = DB::select('SELECT id FROM users WHERE email = :email', ['email' => $userEmail->email]);

        // dump($userN[0]->id);die;
        if ($userN) {
           return  $userN[0]->id;
        } 
        return 0;
    }

   
    private function buildCourse($calendar)
    {
        return [
            'id' => $calendar->id,
            'title' => $calendar->title,
            'description' => $calendar->description,
            'display_date' => $calendar->display_date,
            'color' => $calendar->class_name,
            'calendar_timestamp' => $calendar->calendar_date,
            'start_time' => $calendar->start_time ?: "00:00",
            'end_time' => $calendar->end_time ?: "00:00", 
            'link' => $calendar->link,
            'created_at' => $calendar->createdAt,
            'updated_at' => $calendar->updatedAt,
            'roles' => $this->buildRoles($calendar)  //logic
        
        ];
    }
    private function mapColor($inputColor)
    {
        $colorMapping = [
            'bg-success' => 'success',
            'bg-primary' => 'primary',
            'bg-info'    => 'info',
            'bg-danger'  => 'danger',
            'bg-warning' => 'warning',
            'bg-default' => 'default',
        ];
    
        return $colorMapping[$inputColor] ?? $inputColor;
    }
    private function buildRoles($calendar)
    {
        $roles = [ALL_MEMBER_ROLE];

        if (@$calendar->is_enagic) array_push($roles, ENAGIC_ROLE,);
        if (@$calendar->is_advisor) array_push($roles, ADVISOR_ROLE,);
        if (@$calendar->is_trifecta) array_push($roles, TRIFECTA_ROLE,);
        if (@$calendar->is_core) array_push($roles, CORE_ROLE);
        if (@$calendar->is_active_recruiter) array_push($roles, ACTIVE_RECRUITER_ROLE,);

        return $roles;
    }
}
