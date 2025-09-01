<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function fields(){
        return $this->hasMany(TemplateField::class);
    }
}
