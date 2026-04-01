<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Novelty extends JsonResource
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
    $data['created_at']   = $this->created_at->format('Y-m-d H:i:s');
    $data['excerpt']      = substr($data['description'], 0,80);
    $data['pictures']     = MediaResource::collection($this->getMedia('pictures'));
    $data['pictures_url'] = $this->getMedia('pictures')->pluck('original_url')->toArray();
    
    return $data;
  }
}
