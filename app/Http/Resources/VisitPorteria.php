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
        "name"       => $this->visitor->name,
        "dni"        => $this->visitor->dni,
        "phone"      => $this->visitor->phone,
        "type"       => $this->visitor->type,
        "company"    => $this->visitor->company,
        "arl"        => $this->visitor->arl,
        "eps"        => $this->visitor->eps,
        "arl_eps"    => $this->visitor->arl . ' ' . $this->visitor->eps,
        "picture"    => $this->visitor->getFirstMediaUrl('picture'),
        
        "admin_id"   => $this->admin_id,
        "checkin"    => $this->checkin ? $this->checkin->format('Y-m-d H:i:s') : null,
        "checkout"   => $this->checkout ? $this->checkout->format('Y-m-d H:i:s') : null,
        "extension"  => $this->extension ? ['id' => intval($this->extension->name), 'name'=>$this->extension->name] : null,
        "admin_id"   => $this->admin_id,
        "plate"      => $this->plate,
        "picture"    => $this->visitor->getFirstMediaUrl('picture'),
        "start_date" => $this->start_date ? $this->start_date->format('Y-m-d H:i:s') : null,
        "end_date"   => $this->end_date ? $this->end_date->format('Y-m-d H:i:s') : null,
        "password"   => $this->password,
        "qrcode"     => $this->getFirstMediaUrl('qrcode'),
        "extension_name" => $this->extension_name,
        "authorized_by"  => $this->authorized_by,
        "note"           => $this->note
      ];
    }
}
