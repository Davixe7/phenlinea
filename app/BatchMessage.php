<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function admin(){
      return $this->belongsTo(Admin::class);
    }
}
