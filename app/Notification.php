<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'porteria_id',
        'admin_id',
        'extension_id',
        'content',
        'date',
        'type',
        'count',
    ];
    
    protected $casts = [
        'count' => 'Number'
    ];
    
    public function extension(){
      return $this->belongsTo('App\Extension');
    }
    
    public function scopeSentThisMonth($query){
        return $query->whereMonth('created_at', \Carbon\Carbon::now()->month);
    }
    
    public function scopeBulk($query){
        return $query->whereType('bulk');
    }
}
