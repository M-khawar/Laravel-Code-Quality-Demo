<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferralResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'affiliate_code' => $this->affiliate_code,
            'avatar' => $this->avatar_path,
            'display_name' => @$this->profile->display_name,
            'display_text' => @$this->profile->display_text,
            'head_code' => @$this->profile->head_code,
            'body_code' => @$this->profile->body_code,
        ];
    }
}
