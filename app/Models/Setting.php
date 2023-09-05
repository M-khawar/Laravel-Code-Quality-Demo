<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "group", "name", "value"];

    public $timestamps = false;

    public function scopeSettingFilters($query, string|array $group = null, string $property = null)
    {
        $group = is_array($group) ? $group : [$group];

        $query->when(!empty($group), fn($q) => $q->whereIn("group", $group));

        $query->when(!empty($data['property']), function ($q) use ($property) {
//            $property = $data['property'];

            if (str_contains($property, '.')) {
                [$group, $property] = $this->splitKey($property);
                $q->where(["group" => $group, "name" => $property]);

            } else {
                $q->where("name", $property);
            }
        });

        return $query;
    }
}
