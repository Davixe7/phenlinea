<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Extension extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
        "id"      => $this->id,
        "name"    => $this->name,
        "phone_1" => $this->phone_1,
        "phone_2" => $this->phone_2,
        "phone_3" => $this->phone_3,
        "phone_4" => $this->phone_4,
        "owner_phone" => $this->owner_phone,
        "phones"   => $this->phones,
        "phone_1_name" => $this->phoneOwner  ? $this->phoneOwner->name : 'Desconocido',
        "phone_2_name" => $this->phoneOwner2 ? $this->phoneOwner2->name : 'Desconocido',
        "phone_3_name" => $this->phoneOwner3 ? $this->phoneOwner3->name : 'Desconocido',
        "phone_4_name" => $this->phoneOwner4 ? $this->phoneOwner4->name : 'Desconocido',
      ];
    }
}
