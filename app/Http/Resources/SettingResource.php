<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{

    public function toArray($request)
    {
        return  $this->mapSettings();
    }

    private function mapSettings(): array
    {
        $settings=  $this->resource;

        $settingDictionary=[];
        foreach ($settings as $setting) {
            $settingDictionary = array_merge($settingDictionary, [$setting->name => $setting->value]);
        }

        return $settingDictionary;
    }
}
