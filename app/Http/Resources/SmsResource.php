<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SmsResource extends JsonResource
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
            'date' => \Carbon\Carbon::parse( $this->created_at )->format('Y-m-d H:m:s'),
            'extension_id' => $this->extension_id,
            'type' => $this->type,
            'extension' => $this->extension_id ? $this->extension : null
        ];
    }
}
