<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InvoiceUser;

class Invoice extends JsonResource
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
      'date'    => $this->date,
      'nit'     => $this->nit,
      'number'  => $this->number,
      'total'   => $this->total,
      'paid_at' => $this->paid_at,
      'status'  => $this->status,
      'user'    => new InvoiceUser( $this->whenLoaded('admin') ),
    ];
  }
}
