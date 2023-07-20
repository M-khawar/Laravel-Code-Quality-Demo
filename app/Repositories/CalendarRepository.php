<?php

namespace App\Repositories;

use App\Contracts\Repositories\CalendarRepositoryInterface;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CalendarRepository implements CalendarRepositoryInterface
{
    private Model $calenderModel;

    public function __construct(Model $calender)
    {
        $this->calenderModel = $calender;
    }

    public function store(array $data)
    {
        $calendar= $this->calenderModel::create($data);
//        $calendar->
    }

    public function storeCalenderValidation(array $data)
    {
        return Validator::make($data, [
            "title" => ['required', 'string'],
            "description" => ['nullable'],
            "link" => ['required'],
            "display_date" => ['required'],
            "start_time" => ['required'],
            "end_time" => ['required'],
            "allowed_audience_roles" => ['required', 'exists:' . Role::class . ',uuid']
        ]);
    }


}
