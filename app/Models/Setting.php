<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "group", "name", "value"];

    public $timestamps = false;

    public function scopeSettingFilters($query, array $data)
    {
        $query->when(!empty($data['group']), fn($q) => $q->where("group", $data["group"]));

        $query->when(!empty($data['property']), function ($q) use ($data) {
            $property = $data['property'];

            if (str_contains($property, '.')) {
                [$group, $property] = $this->splitKey($data['property']);
                $q->where(["group" => $group, "name" => $property]);

            } else {
                $q->where("name", $property);
            }
        });

        return $query;
    }
}
