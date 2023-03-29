<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = [
        'visitor_id',
        'extension_id',
    ];
    
    public function visitor(){
        return $this->belongsTo('App\Visitor');
    }
    public function extension(){
        return $this->belongsTo('App\Extension');
    }
}
