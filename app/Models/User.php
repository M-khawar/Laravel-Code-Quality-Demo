<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\Globals\AffiliateCodeGenerator;
use App\Models\Traits\Globals\UserSetting;
use App\Models\Traits\Relations\UserRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUUID, Billable, UserRelations, AffiliateCodeGenerator, UserSetting;

    protected $fillable = [
        'name', 'email', 'password', 'instagram', 'phone', 'avatar', 'affiliate_code',
        'advisor_id', 'is_admin', 'is_advisor', 'is_active_recruiter', 'advisor_date',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'advisor_date'
    ];

    protected $appends = ['avatar_path'];

    public function scopeWhereAffiliate($query, $affiliateCode)
    {
        return $query->where('affiliate_code', $affiliateCode);
    }

    public function scopeWhereDefaultAdvisor($query)
    {
        return $query->where('id', 1);
    }

    protected function avatarPath(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $attributes['avatar_path'] = $attributes['avatar'] ?? asset('assets/images/default_avatar.png');
            }
        );
    }
}
