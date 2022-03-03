<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PushNotificationLogResource extends JsonResource
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
            'id'          => $this->id,
            'title'       => auth()->user()->admin_id ? auth()->user()->admin->name : auth()->user()->name,
            'body'        => $this->body,
            'created_at'  => $this->created_at
        ];
    }
}
