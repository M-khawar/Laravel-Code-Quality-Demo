<?php

namespace App\Models;

use App\Models\Traits\Relations\ChatRelation;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory, HasUUID, ChatRelation;

    protected $table = "chat";

    protected $fillable = ['message'];

}
