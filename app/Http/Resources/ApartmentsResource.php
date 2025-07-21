<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'admin_id'     => $this->admin_id,
            'name'         => $this->name,
            'phone_1'      => $this->phone_1,
            'phone_2'      => $this->phone_2,
            'observation'  => $this->observation,
            'deposit'      => $this->deposit,
        ];
    }
}
