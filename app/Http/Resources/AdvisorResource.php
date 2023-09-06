<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvisorResource extends JsonResource
{

    public function toArray($request)
    {
        $data = [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'affiliate_code' => $this->affiliate_code,
            'phone' => $this->phone,
            'avatar' => $this?->avatar?->media_path,
        ];

        if (@$this->settings) {
            $data = array_merge($data, $this->getSchedulingLink());
        }

        return $data;
    }

    private function getSchedulingLink()
    {
        $settingDictionary = [];
        foreach ($this->settings as $setting) {
            $settingDictionary = array_merge($settingDictionary, [$setting->name => $setting->value]);
        }

        return $settingDictionary;

    }
}
