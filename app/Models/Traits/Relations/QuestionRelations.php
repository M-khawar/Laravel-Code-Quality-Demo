<?php

namespace App\Models\Traits\Relations;

use App\Models\Answer;
use App\Models\Video;

trait QuestionRelations
{

    public function answer()
    {
        return $this->hasOne(Answer::class, 'question_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }
}
