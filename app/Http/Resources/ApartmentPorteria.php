<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentPorteria extends JsonResource
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
        "id" => $this->id,
        "name" => $this->name,
        "parking_numbers_str" => $this->parking_numbers_str,
        "pets_count" => $this->pets_count,
        "plates" => $this->plates,
        "admin_id" => $this->admin_id,
        "residents" => $this->residents,
        "phone_owner" => $this->phoneOwner,
        "phone_owner_2" => $this->phoneOwner2,
        "phone_owner_3" => $this->phoneOwner3,
        "phone_owner_4" => $this->phoneOwner4,
      ];
    }
}
