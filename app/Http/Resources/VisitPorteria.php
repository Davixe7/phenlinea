<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitPorteria extends JsonResource
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
        "type"       => $this->type,
        "company"    => $this->company,
        "arl"        => $this->arl,
        "eps"        => $this->eps,
        "arl_eps"    => trim("$this->arl $this->eps"),
        "plate"      => $this->plate,
        "checkin"    => $this->checkin,
        "checkout"   => $this->checkout,
        "extension"  => $this->extension ? ['id' => intval($this->extension->name), 'name'=>$this->extension->name] : null,
        "admin_id"   => $this->admin_id,
        "picture"    => $this->getFirstMediaUrl('picture'),
        "start_date" => $this->start_date->format('Y-m-d H:i:s'),
        "end_date"   => $this->end_date->format('Y-m-d H:i:s'),
        "password"   => $this->password,
        "qrcode"     => $this->getFirstMediaUrl('qrcode')
      ];
    }
}
