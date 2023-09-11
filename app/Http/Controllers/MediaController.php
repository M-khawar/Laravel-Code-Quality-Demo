<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    private mixed $mediaModel;

    public function __construct()
    {
        $this->mediaModel = app(Media::class);
    }

    public function uploadMedia(Request $request)
    {
        try {
            $request->validate([
                "image" => ["required", "image", "mimes:jpeg,jpg,png", "max:" . config("default_settings.max_image_size")],
            ]);

            $filePath = $this->mediaModel->storeMediaByHashName($request->image, "course-thumbnail");

            $media = $this->mediaModel::create([
                "path" => $filePath,
                "source" => SPACES_STORAGE,
                "archived" => false,
            ]);

            $media = new MediaResource($media);

            return response()->success(__("messages.media.uploaded"), $media);


        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
