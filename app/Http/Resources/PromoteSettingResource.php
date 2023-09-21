<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoteSettingResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'display_name' => $this->display_name,
            'display_text' => $this->display_text,
            'head_code' => $this->head_code,
            'body_code' => $this->body_code,
            'admin_settings' => new SettingResource($this->whenLoaded("adminSettings"))
        ];
    }
}
