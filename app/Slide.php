<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = ['title', 'body', 'picture'];
    protected $hidden   = ['created_at', 'updated_at'];
    
    
}
