<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $media = $this->getFirstMedia('picture');

        $data = parent::toArray($request);
        return array_merge($data, [
            'original' => $media?->getUrl('original'),
            'thumb' => $media?->getUrl('thumb'),
        ]);
    }
}
