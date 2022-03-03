<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtensionsExport extends JsonResource
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
          'id' => $this->id,
          'apto' => $this->name,
          'linea1' => $this->phone_1,
          'linea2' => $this->phone_2,
          'linea3' => $this->phone_3,
          'linea4' => $this->phone_4,
          'tel_propietario' => $this->owner_phone,
          'mascotas' => $this->pets_count,
          'parqueadero_1' => $this->parking_number1,
          'parqueadero_2' => $this->parking_number2,
          'vehiculos' => implode(",", collect( $this->vehicles )
                                              ->pluck('plate')
                                              ->toArray()),
          'deposito' => $this->has_deposit,
        ];
    }
}
