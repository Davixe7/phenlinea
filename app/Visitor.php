<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'extension_id',
        'name',
        'dni',
        'authorized_at',
        'plate',
    ];
    
    protected $appends = [
        'qr'
    ];
    
    public function extension(){
        return $this->belongsTo('App\Extension');
    }
    
    public function checkins(){
        return $this->hasMany('App\Checkin');
    }
    
    public function getQrAttribute(){
        $path = public_path("qr/visitor_{$this->id}_qr.png");
        
        $data = [
            'name' => $this->name,
            'dni'  => $this->dni,
            'authorized_at'  => $this->authorized_at,
            'plate'  => $this->plate
        ];
        
        if( !is_file( $path ) ){
          \QrCode::size(250)
            ->color(83,151,212)
            ->backgroundColor(0,0,0)
            ->style('round')
            ->errorCorrection('H')
            ->merge('https://phenlinea.com/img/logo_qr.png', .3, true)
            ->format('png')
            ->generate( json_encode( $data ) , $path);
        }
    
        return [
          'path' => $path,
          'url'  => url("qr/visitor_{$this->id}_qr.png")
        ];
    }
}
