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
      // return [
      //   'Name'              => $this->name,
      //   'Email'             => $this->id,
      //   'Card number'       => $this->card,
      //   'ID number'         => $this->dni,
      //   'Gender'            => '',
      //   'Personnel number'  => '',
      //   'Company Name'      => '',
      //   'Bed Code'          => '',
      //   'Access start time' => '',
      //   'Access end time'   => '',
      //   'Building name*'    => $this->extension->admin->name,
      //   'Room name*'        => $this->extension->name,
      //   'UUID'              => $this->id,
      //   'Identity Type'     => '',
      //   'Age'               => $this->age,
      // ];

      return
      [
        'apto'           => $this->extension->name,
        'nombre'         => $this->name,
        'edad'           => $this->age,
        'dni'            => $this->dni,
        'es_propietario' => $this->is_owner,
        'es_residente'   => $this->is_resident,
      ];
    }
}
