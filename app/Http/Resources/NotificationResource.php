<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['date'] = \Carbon\Carbon::parse($this->date)->format('Y-m-d');
        $data['created_at'] = \Carbon\Carbon::parse($this->created_at)->format('Y-m-d H:m:s');
        return $data;
    }
}
