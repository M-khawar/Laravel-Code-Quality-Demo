<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunnelStepsCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'funnel_type',
        'funnel_step',
        'code',
    ];

    protected $casts = [
        'funnel_type' => 'string',
        'funnel_step' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
