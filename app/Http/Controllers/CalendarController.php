<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\CalendarRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function __construct(public CalendarRepositoryInterface $calendarRepository)
    {
    }

    public function index()
    {
        dd("index");
    }

    public function store(Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();

            $this->calendarRepository->storeCalenderValidation($data)->validate();
            $this->calendarRepository->store($data);

            DB::commit();

            return response()->message("Adsfsad");

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }

    }

    public function edit(Request $request, $uuid)
    {

    }

    public function destroy($uuid)
    {

    }


}
