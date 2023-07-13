<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoteSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'display_name' => $this->display_name,
            'display_text' => $this->display_text,
            'head_code' => $this->head_code,
            'body_code' => $this->body_code,
        ];
    }
}
