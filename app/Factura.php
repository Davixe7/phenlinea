<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
  protected $fillable = [
    'apto',
    'admin_id',
    'link',
    'note',
    'periodo',
    'emision',
    'limite',
    'concepto1',
    'vencido1',
    'actual1',
    'concepto2',
    'vencido2',
    'actual2',
    'concepto3',
    'vencido3',
    'actual3'
  ];
  
  protected $casts = [
    'total' => 'Integer'
  ];
  
  protected $appends = ['total'];

  protected $hidden  = [
    'created_at',
    'updated_at'
  ];

  public function admin(){
    return $this->belongsTo('App\Admin');
  }

  public function getApartmentAttribute(){
    return Extension::where('admin_id', $this->admin_id)->where('name', $this->apto)->first();
  }

  public function getPeriodoEsAttribute(){
    return ucfirst( \Carbon\Carbon::parse( $this->periodo )->translatedFormat('F d') );
  }
  public function getEmisionEsAttribute(){
    return ucfirst( \Carbon\Carbon::parse( $this->emision )->translatedFormat('F d') );
  }
  public function getLimiteEsAttribute(){
    return ucfirst( \Carbon\Carbon::parse( $this->limite )->translatedFormat('F d') );
  }
  
  public function getTotalAttribute(){
    $total = 0;
    for($i = 1; $i < 7; $i++){
        $total += $this->{"vencido$i"} + $this->{"actual$i"};
    }
    return $total;
  }
}
