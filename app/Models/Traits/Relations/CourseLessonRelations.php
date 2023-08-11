<?php

namespace App\Models\Traits\Relations;

use App\Models\{User, Video};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CourseLessonRelations
{
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function lessonUsers()
    {
        return $this->belongsToMany(User::class, 'completed_lessons', 'lesson_id', 'user_id')
            ->withTimestamps()->withPivot('watched');
    }
}
