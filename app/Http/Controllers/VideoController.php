<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;
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
}
