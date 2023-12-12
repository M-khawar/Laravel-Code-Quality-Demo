<?php

namespace App\Models\Traits\Relations;

use App\Models\{Address, Chat, Lead, Media, Note, PageView, Profile, User};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne, MorphOne};

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

    /**
     * Overriding Spatie's roles relation
     */
    public function roles(): BelongsToMany
    {
        return $this->spatieRoles()->withTimestamps();
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class, "affiliate_id");
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, "affiliate_id");
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class, "affiliate_id", "id");
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function subscription($name = 'default')
    {
        return $this->subscriptions->where('name', $name)->whereNotIn("stripe_status", ["stale"])->first();
    }
}
