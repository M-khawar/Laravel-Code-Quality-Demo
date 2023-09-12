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

    public function notificationSettings()
    {
        return $this->settings()->where('group', NOTIFICATION_SETTING_GROUP);
    }

    public function settingFilters(string|array $group = null, string $property = null)
    {
        $query = $this->settings();

        $group = is_array($group) ? $group : [$group];


        $query->when(!empty($group), fn($q) => $q->whereIn("group", $group));

        $query->when(!empty($data['property']), function ($q) use ($property) {

            if (str_contains($property, '.')) {
                [$group, $property] = $this->splitKey($property);
                $q->where(["group" => $group, "name" => $property]);

            } else {
                $q->where("name", $property);
            }
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

    /**
     * It will update single group's multiple properties
     */
    public function updateMultipleProperties(string $group, string|array $properties)
    {
        $values = [];

        if (!is_array($properties)) {
            $properties = [$properties];
        }

        $keys = array_keys($properties);
        $isAssociativeArr = $keys != array_keys($keys) ? true : false;

        if ($isAssociativeArr) {
            $values = $properties;
            $properties = $keys;
        }

        $settings = $this->settings()
            ->where('group', $group)
            ->whereIn('name', $properties)
            ->get();

        $settings->each->mapValueForMassUpdate($values);

        $this->settings()->upsert($settings->toArray(), ["id"]);
    }

    public function updateProperty(string $group, string $property, $payload)
    {
        $setting = $this->settings()
            ->where('group', $group)
            ->where('name', $property)
            ->first();

        throw_if(!$setting, "Setting property `{$group}.{$property}` not found");

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
