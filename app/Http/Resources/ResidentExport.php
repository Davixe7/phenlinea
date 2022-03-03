<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResidentExport extends JsonResource
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
        'extension' => $this->extension->name,
        'nombre' => $this->name,
        'edad' => $this->age,
        'dni' => $this->dni,
        'es_propietario' => $this->is_owner,
        'es_residente' => $this->is_resident,
      ];
    }
}
