<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Visit extends JsonResource
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
        "fecha"      => $this->created_at->format('Y-m-d H:i:s'),
        "apto"       => $this->extension_name,
        "password"   => $this->password,
        "placa"      => $this->plate,

        "nombre"     => $this->visitor->name,
        "cedula"     => $this->visitor->dni,
        "teléfono"   => $this->visitor->phone   ?: '',
        "tipo"       => $this->visitor->type,
        "compañia"   => $this->visitor->company ?: '',
        "arl"        => $this->visitor->arl     ?: '',
        "eps"        => $this->visitor->eps     ?: '',
        "foto"       => $this->visitor->getFirstMediaUrl('picture'),

        "autorizado por" => $this->authorized_by,
        "nota"           => $this->note
      ];
    }
}
