<?php

namespace App\Models;

use App\Models\Traits\Relations\QuestionRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory, HasUUID, QuestionRelations;

    protected $fillable = ['text', 'position', 'vimeo_link', 'is_answerable'];

}
