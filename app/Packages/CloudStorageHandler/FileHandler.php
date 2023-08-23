<?php

namespace App\Packages\CloudStorageHandler;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileHandler
{
    protected string $disk = 'spaces';

    public function storeMediaByHashName($media_resource, $path = 'images-media')
    {
        try {
            return Storage::disk($this->disk)->putFile($path, $media_resource, 'public');

        } catch (\Exception $exception) {
            throw new \Exception('Unable to store media', 400);
        }
    }

    public function storeMedia($media_resource, ?string $path = 'media/', $name)
    {
        try {
            $returnable_path = $path . $name;
            Storage::disk($this->disk)->put($returnable_path, $media_resource, 'public');
            return $returnable_path;

        } catch (\Exception $exception) {
            throw new \Exception('Unable to store media', 400);
        }
    }

    public function deleteMedia($path)
    {
        $is_deleted = Storage::disk($this->disk)->delete($path);

        if (!$is_deleted) {
            return false;
        }

        return true;
    }

    protected function mediaExists($path)
    {
        return Storage::disk($this->disk)->exists($path);
    }

    protected function mediaUrl($path)
    {
        if ($this->mediaExists($path)) {
            $path = Storage::disk($this->disk)->url($path);
        }

        return $path;
    }

    public function getMediaPath($column, $default = null)
    {
        $path = $default;

        if ($this->{$column} && $this->mediaExists($this->{$column})) {
            $path = $this->mediaUrl($this->{$column});

        } elseif ($this->{$column} && file_exists(public_path($this->{$column}))) {
            $path = asset($this->{$column});

        } elseif ($this->{$column} && file_exists('storage/' . $this->{$column})) {
            $path = asset('storage/' . $this->{$column});
        }

        return $path;
    }

    //$filename could be a filename with extension
    public function uploadSingleMedia(UploadedFile $uploadedFile, $folder, $filename = null, $disk = null)
    {
        if (is_null($disk)) {
            $disk = env('FILESYSTEM_DRIVER');
        }

        if (is_null($filename)) {
            $filename = time() . rand(1000, 9999) . '.' . $uploadedFile->getClientOriginalExtension();
        }

        $file = $uploadedFile->storeAs($folder, $filename, $disk);

        return $filename;
    }

    public function deleteMediaByPath($path, $disk = null)
    {
        if (is_null($disk)) {
            $disk = env('FILESYSTEM_DRIVER');
        }

        $is_deleted = Storage::disk($disk)->delete($path);

        if (!$is_deleted) {
            return false;
        }

        return true;
    }
}
