<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource
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
        unset($data['api_token']);
        return $data;
    }
}
