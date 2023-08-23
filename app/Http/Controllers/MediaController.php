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
