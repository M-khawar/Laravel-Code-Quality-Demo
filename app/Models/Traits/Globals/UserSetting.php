<?php

namespace App\Models\Traits\Globals;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserSetting
{
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function settingFilters(array $data)
    {
        $query = $this->settings();

        $query->when(!empty($data['group']), fn($q) => $q->where("group", $data["group"]));

        $query->when(!empty($data['property']), function ($q) use ($data) {
            [$group, $property] = $this->splitKey($data['property']);

            $q->where(["group" => $group, "name" => $property]);
        });

        return $query;
    }

    /*** Helper methods ***/
    public function splitKey($property)
    {
        return [$group, $name] = explode('.', $property);
    }

    public function getPropertiesInGroup(string $group)
    {
        return $this->settings()->where('group', $group)->get(['name', "value"]);
    }

    public function checkIfPropertyExists(string $group, string $name): bool
    {
        return $this->settings()
            ->where('group', $group)
            ->where('name', $name)
            ->exists();
    }

    public function updateOrInsertProperty(string $group, string $name, $payload)
    {
        return $this->settings()
            ->updateOrCreate(
                ['group' => $group, 'name' => $name],
                ['value' => $payload]
            );
    }

    public function updateProperty(string $group, string $name, $payload)
    {
        $setting = $this->settings()
            ->where('group', $group)
            ->where('name', $name)
            ->first();

        throw_if(!$setting, "Setting property `{$group}.{$name}` not found");

        $setting->update([
            'value' => $payload,
        ]);

        return $setting;
    }

    public function deleteProperty(string $group, string $name)
    {
        return $this->settings()->where('group', $group)
            ->where('name', $name)
            ->delete();
    }
}
