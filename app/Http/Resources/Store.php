<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Store extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $store = parent::toArray($request);
        $store['logo'] = new MediaResource( $this->getFirstMedia('logo') );
        $store['pictures'] = MediaResource::collection( $this->getMedia('pictures') );
        $store['menu']     = ProductResource::collection( $this->whenLoaded('menu') );
        return $store;
    }
}
