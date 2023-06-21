<?php

namespace App\Models\Traits\Relations;

use App\Models\Answer;

trait QuestionRelations
{

    public function answer()
    {
        return $this->hasOne(Answer::class, 'question_id');
    }
}
