<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Factura extends JsonResource
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
          'apto' => $this->apto,
          'admin_id' => $this->admin_id,

          'concepto1' => $this->concepto1,
          'vencido1'  => $this->vencido1,
          'actual1'   => $this->actual1,

          'concepto2' => $this->concepto2,
          'vencido2'  => $this->vencido2,
          'actual2'   => $this->actual2,

          'concepto3' => $this->concepto3,
          'vencido3'  => $this->vencido3,
          'actual3'   => $this->actual3,

          'id' => str_pad($this->id,4,'0',STR_PAD_LEFT),
          'periodo' => \Carbon\Carbon::parse($this->periodo)->translatedFormat('F d'),
          'emision' => \Carbon\Carbon::parse($this->periodo)->translatedFormat('F d'),
          'limite'  => \Carbon\Carbon::parse($this->periodo)->translatedFormat('F d')
        ];
    }
}
