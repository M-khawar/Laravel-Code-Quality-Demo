<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'question_uid' => $this->uuid,
            'question' => $this->text,
            'position' => $this->position,
            'vimeo' => $this->vimeo_link,
            'is_answerable' => $this->is_answerable,
            'answer' => @$this->answer->text,
            'watched' => @$this->answer->watched,
        ];
    }
}
