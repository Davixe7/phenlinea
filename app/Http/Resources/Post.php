<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['attachments'] = MediaResource::collection( $this->getMedia('attachments') );
        $data['pictures']    = MediaResource::collection( $this->getMedia('pictures') );
        return $data;
    }
}
