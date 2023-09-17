<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\Globals\{AffiliateCodeGenerator, FunnelGenerator, Searchable, UserSetting};
use App\Models\Traits\Relations\UserRelations;
use App\Packages\StripeWrapper\Contracts\{DeleteOldCardOnUpdate, HasPaidTrail};
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements DeleteOldCardOnUpdate, HasPaidTrail
{
    use HasApiTokens, HasFactory, Notifiable, HasUUID, Billable, UserRelations, AffiliateCodeGenerator, UserSetting,
        FunnelGenerator, Searchable;

    use HasRoles {
        UserRelations::roles insteadof HasRoles;
        HasRoles::roles as spatieRoles;
    }

    protected $fillable = [
        'name', 'email', 'password', 'instagram', 'phone', 'avatar_id', 'affiliate_code', 'funnel_type',
        'advisor_id', 'affiliate_id',
    ];

    protected $searchable_columns = [
        'name', 'email', 'instagram', 'phone', 'affiliate_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    protected $with = ["avatar"];

    public function scopeWhereAffiliate($query, $affiliateCode)
    {
        return $query->where('affiliate_code', $affiliateCode);
    }

    public function scopeWhereDefaultAdvisor($query)
    {
        return $query->where('id', config('default_settings.default_advisor'));
    }

    public function scopeExcludeAdmins($query)
    {
        return $query->whereNotIn('id', [config('default_settings.default_advisor')]);
    }

    public static function findOrFailUserByUuid(string $uuid)
    {
        return static::byUUID($uuid)->firstOrFail();
    }

    public static function getAffiliateByCode($affiliateCode)
    {
        return self::query()
            ->when(!empty($affiliateCode), fn($q) => $q->whereAffiliate($affiliateCode))
            ->when(empty($affiliateCode), fn($q) => $q->whereDefaultAdvisor())
            ->first();
    }

    protected function hasActiveSubscription(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->subscribed(config('cashier.subscription_name'));
            }
        );
    }
}
