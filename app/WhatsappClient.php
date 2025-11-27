<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappClient extends Model
{
    use HasFactory;

    public function instances(){
        return $this->hasMany(WhatsappInstance::class);
    }
}
