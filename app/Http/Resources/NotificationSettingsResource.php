<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationSettingsResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->buildNotificationDictionary();
    }

    private function buildNotificationDictionary()
    {
        $notifications = $this->resource;

        $notificationsData = [];
        foreach ($notifications as $notification) {
            $notificationsData = array_merge($notificationsData, [$notification->name => (bool)$notification->value]);
        }

        return $notificationsData;
    }
}
