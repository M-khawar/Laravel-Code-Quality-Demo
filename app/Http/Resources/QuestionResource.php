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
            'video_link' => @$this->video->link,
            'video_source' => @$this->video->source,
            'is_answerable' => $this->is_answerable,
            'answer' => @$this->answer->text,
            'watched' => @$this->answer->watched ? true : false,
        ];
    }
}
