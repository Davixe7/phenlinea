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
        "_email"      => $this->_email,
        "_password"   => $this->_password,
        "phones"   => $this->phones,
      ];
    }
}
