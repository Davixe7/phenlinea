<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class PetitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $read_at = $this->read_at ? Carbon::parse($this->read_at)->locale('es')->isoFormat('ddd D MMM h:mma') : null;
        $read_at = $read_at ? preg_replace_callback('/^(\w{3})/', fn($m) => Str::ucfirst($m[1]), $read_at) : null;
        $read_at = $read_at ? preg_replace_callback('/\s(\w{3})\./', fn($m) => ' ' . Str::ucfirst($m[1]) . '.', $read_at) : null;

        $replied_at = $this->replied_at ? Carbon::parse($this->replied_at)->locale('es')->isoFormat('ddd D MMM h:mma') : null;
        $replied_at = $replied_at ? preg_replace_callback('/^(\w{3})/', fn($m) => Str::ucfirst($m[1]), $replied_at) : null;
        $replied_at = $replied_at ? preg_replace_callback('/\s(\w{3})\./', fn($m) => ' ' . Str::ucfirst($m[1]) . '.', $replied_at) : null;

        return array_merge($data, [
            'read_at'    => $read_at,
            'replied_at' => $replied_at
        ]);
    }
}
