<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\CalendarRepositoryInterface;
use App\Http\Resources\CalendarResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function __construct(public CalendarRepositoryInterface $calendarRepository)
    {
    }

    public function index(Request $request)
    {
        try {
            $date = $request->date;
            $calendarEvents = $this->calendarRepository->fetchEvents($date);

            $data = CalendarResource::collection($calendarEvents);
            return response()->success(__("messages.calendar.fetched"), $data);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();
            $this->calendarRepository->storeCalenderValidation($data)->validate();
            $calendarEvent = $this->calendarRepository->store($data);
            DB::commit();

            $data = new CalendarResource($calendarEvent);
            return response()->success(__("messages.calendar.created"), $data);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }

    }

    public function edit(Request $request, $uuid)
    {
        try {
            $calendar = $this->calendarRepository->calendarByUuid($uuid);
            $data = $request->input();

            DB::beginTransaction();
            $this->calendarRepository->editCalenderValidation($data)->validate();
            $calendarEvent = $this->calendarRepository->edit($calendar, $data);
            DB::commit();

            $data = new CalendarResource($calendarEvent);
            return response()->success(__("messages.calendar.edited"), $data);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function destroy($uuid)
    {
        try {
            DB::beginTransaction();
            $this->calendarRepository->deleteCalendar($uuid);
            DB::commit();

            return response()->message(__("messages.calendar.deleted"));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }


}
