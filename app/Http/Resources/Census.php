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
        'deposit'         => $this->deposit,
        
        'has_own_parking' => $this->has_own_parking,
        'parking_number1' => $this->parking_number1,
        'parking_number2' => $this->parking_number2,
        
        'observation'     => $this->observation,
        
        'vehicles'  => $this->vehicles,
        '_password' => $this->_password,
        '_email'    => $this->_email,
        'resident_id'      => $this->resident_id,
        'resident_id_2'    => $this->resident_id_2,
        'resident_id_3'    => $this->resident_id_3,
        'resident_id_4'    => $this->resident_id_4,

        'phone_owner'      => $this->phone_owner,
        'phone_owner_2'    => $this->phone_owner_2,
        'phone_owner_3'    => $this->phone_owner_3,
        'phone_owner_4'    => $this->phone_owner_4,
      ];
    }
}
