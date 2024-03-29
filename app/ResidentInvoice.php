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
    'emision' => 'date'
  ];
  
  protected $appends = ['total', 'paid', 'pending', 'formatted_id'];

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

  function getFormattedIdAttribute(){
    return str_pad($this->id, 4, '0', STR_PAD_LEFT);
  }

  public function getPeriodoEsAttribute(){
    return ucfirst( \Carbon\Carbon::parse( $this->resident_invoice_batch->periodo )->isoFormat('MMMM D') );
  }
  public function getEmisionEsAttribute(){
    return ucfirst( \Carbon\Carbon::parse( $this->resident_invoice_batch->emision )->isoFormat('MMMM D') );
  }
  public function getLimiteEsAttribute(){
    return ucfirst( \Carbon\Carbon::parse( $this->resident_invoice_batch->limite )->isoFormat('MMMM D') );
  }

}
