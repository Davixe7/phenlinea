<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtensionCensus extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // return parent::toArray($request);
      return [
        'id'          => $this->id,
        'name'        => $this->name,
        'phone_1'     => $this->phone_1,
        'phone_2'     => $this->phone_2,
        'owner_phone' => $this->owner_phone,
        'pets_count'  => $this->pets_count,
        'has_deposit' => $this->has_deposit,
        'has_own_parking' => $this->has_own_parking,
        'vehicles'    => ($this->vehicles) ? count($this->vehicles) : 0,
        'adults'      => $this->adults,
        'minors'      => $this->minors,
      ];
    }
}
