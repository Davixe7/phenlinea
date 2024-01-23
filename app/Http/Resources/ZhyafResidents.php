<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ZhyafResidents extends JsonResource
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
        'Name*'             => $this->name,
        'Email'             => $this->id,
        'Card number'       => $this->tags,
        'ID number'         => $this->dni,
        'Gender'            => '',
        'Personnel number'  => '',
        'Company Name'      => '',
        'Bed Code'          => '',
        'Access start time' => '',
        'Access end time'   => '',
        'Building name*'    => $this->extension->admin->name,
        'Room name*'        => $this->extension->name,
        'Identity Type'     => '',
        'UUID'              => $this->id,
        'Age'               => $this->age,
      ];
    }
}
