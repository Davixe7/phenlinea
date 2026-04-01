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
      $data = parent::toArray($request);
      return array_merge($data, [
        "phone_1_name" => $this->phoneOwner  ? $this->phoneOwner->name : 'Desconocido',
        "phone_2_name" => $this->phoneOwner2 ? $this->phoneOwner2->name : 'Desconocido',
        "phone_3_name" => $this->phoneOwner3 ? $this->phoneOwner3->name : 'Desconocido',
        "phone_4_name" => $this->phoneOwner4 ? $this->phoneOwner4->name : 'Desconocido',
      ]);
    }
}
