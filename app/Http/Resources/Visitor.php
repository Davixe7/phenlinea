<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Visitor extends JsonResource
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
        "id"         => $this->id,
        "name"       => $this->name,
        "dni"        => $this->dni,
        "phone"      => $this->phone,
        "plate"      => $this->plate,
        "type"       => $this->type,
        "company"    => $this->company,
        "arl"        => $this->arl,
        "eps"        => $this->eps,
        "arl_eps"    => trim("$this->arl $this->eps"),
        "picture"    => $this->getFirstMediaUrl('picture')
      ];
    }
}
