<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{

    public function toArray($request)
    {
        return $this->mapSettings();
    }

    private function mapSettings(): array
    {
        $settings = $this->resource;

        $settingDictionary = [];
        foreach ($settings as $setting) {
            $data = in_array($setting->value, ["0", "1"]) ? [$setting->name => (bool)$setting->value] : [$setting->name => $setting->value];
            $settingDictionary = array_merge($settingDictionary, $data);
        }

        return $settingDictionary;
    }
}
