<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Petition extends JsonResource
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
        $data['attachments'] = $this->getMedia('attachments')->map( function( $media ){ return $media->original_url; } );
        return $data;
    }
}
