<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory, HasUUID;

    protected $fillable= ['user_id', 'question_id', 'watched', 'text'];

    public function scopeFilterByUser($query, $userID)
    {
        return $query->where('user_id', $userID);
    }
}
