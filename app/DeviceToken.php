<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $fillable = [
        'extension_id',
        'token_id',
    ];
}
