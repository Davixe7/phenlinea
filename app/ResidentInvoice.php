<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentInvoice extends Model
{
  protected $fillable = [
    'apto',
    'resident_invoice_batch_id',
    'extension_id',
  ];
  
  protected $casts = [
    'total'   => 'double',
    'paid'    => 'double',
    'pending' => 'double',
  ];
  
  protected $appends = ['total', 'paid', 'pending'];

  protected $hidden  = [
    'created_at',
    'updated_at'
  ];

  function resident_invoice_batch(){
    return $this->belongsTo(ResidentInvoiceBatch::class);
  }

  function extension(){
    return $this->belongsTo(Extension::class);
  }

  function resident_invoice_items(){
    return $this->hasMany(ResidentInvoiceItem::class);
  }

  function resident_invoice_payments(){
    return $this->hasMany(ResidentInvoicePayment::class);
  }

  function getTotalAttribute(){
    return $this->resident_invoice_items->reduce(fn(?int $carry, $item) => $carry + ($item->pending + $item->current));
  }

  function getPaidAttribute(){
    return $this->resident_invoice_payments->reduce(fn(?float $carry, $payment) => $carry + $payment->amount);
  }

  function getPendingAttribute(){
    return $this->total - $this->paid;
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
