<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateField extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $hidden = ['created_at', 'updated_at'];
}
