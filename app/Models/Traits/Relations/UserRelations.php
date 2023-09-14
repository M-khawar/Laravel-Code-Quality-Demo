<?php

namespace App\Models\Traits\Relations;

use App\Models\{Address, Chat, Media, Profile, User};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne, MorphOne};

trait UserRelations
{
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->withDefault();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Media::class, "avatar_id")->withDefault(["path" => asset('assets/images/default_avatar.png')]);
    }

    public function chat(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}
