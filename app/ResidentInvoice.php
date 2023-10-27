<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentInvoice extends Model
{
  protected $fillable = [
    'apto',
    'resident_invoice_batch_id',
    'concepto1',
    'vencido1',
    'actual1',
    'concepto2',
    'vencido2',
    'actual2',
    'concepto3',
    'vencido3',
    'actual3',
    'ultimo_pago',
    'fecha_ultimo_pago'
  ];
  
  protected $casts = [
    'total' => 'double'
  ];
  
  protected $appends = ['total'];

  protected $hidden  = [
    'created_at',
    'updated_at'
  ];

  function resident_invoice_batch(){
    return $this->belongsTo(ResidentInvoiceBatch::class);
  }

  function apartment(){
    return $this->belongsTo(Extension::class, 'apto', 'name');
  }

  function getTotalAttribute(){
    $total = 0;
    for ($i=1; $i < 7; $i++) { 
      $total += $this->{"vencido{$i}"} + $this->{"actual{$i}"};
    }
    return $total;
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

}
