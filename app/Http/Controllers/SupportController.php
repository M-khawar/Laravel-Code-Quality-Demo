<?php

namespace App\Http\Controllers;

use App\Notifications\SupportedRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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

            $user = currentUser();

            $request->merge(["email" => $user->email]);
            $data = $request->input();

            Notification::route('mail', env("SUPPORT_EMAIL"))->notify(new SupportedRequestNotification($data));

            return response()->message(__('messages.support.ticket_submit'));

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }
}
