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
        'visitors' => $this->visitors()->where('authorized_at', '>=' ,now()->format('Y-m-d'))->get()
      ];
    }
}
