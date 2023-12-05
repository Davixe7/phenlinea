<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ZhyafExtension extends JsonResource
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
          'buildingName' => $this->admin->name,
          'buildingCode' => $this->admin->id,
          'roomName'     => $this->name,
          'roomCode'     => $this->name,
          'roomUuid'     => $this->id
        ];
    }
}
