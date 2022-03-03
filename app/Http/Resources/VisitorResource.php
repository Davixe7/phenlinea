<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Extension;

class VisitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $extension = Extension::find( $this->extension_id )->select(['name'])->first();
        return array_merge( parent::toArray($request), ['extension' => $extension] );
    }
}
