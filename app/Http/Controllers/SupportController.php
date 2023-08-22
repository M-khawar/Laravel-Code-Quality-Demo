<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function issueCategories()
    {
        $categories = array(
            ["title" => "General",],
            ["title" => "Issue with advisor/downline/lead/enagic"]
        );

        return response()->success(__('messages.support.categories'), $categories);
    }

    public function submitTicket(Request $request)
    {
        try {
            $request->validate([
                "category" => ['required', "string"],
                "subject" => ['required', "string"],
                "message" => ['required', "string"],
            ]);

            return response()->message(__('messages.support.ticket_submit'));

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }
}
