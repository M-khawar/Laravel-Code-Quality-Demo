<?php

namespace App\Models\Traits\Relations;

use App\Models\Video;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CourseLessonRelations
{
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'video_id');
    }
}
