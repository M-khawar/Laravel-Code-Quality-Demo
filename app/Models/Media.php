<?php

namespace App\Models;

use App\Packages\CloudStorageHandler\FileHandler;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory, HasUUID, FileHandler;

    protected $fillable = ["source", "path", "archived"];

    protected $appends = ["media_path"];

    public static function findOrFailByUuid(string $uuid)
    {
        return static::byUUID($uuid)->firstOrFail();
    }

    protected function mediaPath(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $path = null;

                if ($attributes['path'] && filter_var($attributes['path'], FILTER_VALIDATE_URL) !== false) {
                    $path = $attributes['path'];

                } elseif (filter_var($attributes['path'], FILTER_VALIDATE_URL) === false) {
                    $path = config("filesystems.disks.spaces.cdn_endpoint") . '/' . $attributes['path'];

                } else {
                    $path = asset('assets/images/no_image_available.jpg');
                }

                return $path;
            }
        );
    }
}
