<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Http\Resources\VideoResource;
use App\Models\{Setting, Video};
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function getVideoBySlug($slug)
    {
        try {
            $video = Video::findBySlug($slug);
            $data = new VideoResource($video);

            return response()->success(__('messages.video.fetched_successfully'), $data);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function getLiveCall()
    {
        try {
            $adminSettings = Setting::settingFilters(ADMIN_SETTINGS_GROUP)->get();
            $adminSettings = new SettingResource($adminSettings);

            return response()->success(__('messages.admin_settings.fetched'), $adminSettings);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }
}
