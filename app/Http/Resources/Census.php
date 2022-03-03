<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Census extends JsonResource
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
        'id'              => $this->id,
        
        'phone_1'         => $this->phone_1,
        'phone_2'         => $this->phone_2,
        'phone_3'         => $this->phone_3,
        'phone_4'         => $this->phone_4,
        
        'name'            => $this->name,
        'email'           => $this->email,
        'residents'       => $this->residents,
        'owner_phone'     => $this->owner_phone,
        'owner_name'      => $this->owner_name,
        'emergency_contact'     => $this->emergency_contact,
        'emergency_contact_name'     => $this->emergency_contact_name,
        'pets_count'      => $this->pets_count,
        'has_deposit'     => $this->has_deposit,
        
        'has_own_parking' => $this->has_own_parking,
        'parking_number1' => $this->parking_number1,
        'parking_number2' => $this->parking_number2,
        
        'observation'     => $this->observation,
        
        'vehicles'  => $this->vehicles,
        '_password' => $this->_password,
        '_email'    => $this->_email
      ];
    }
}
