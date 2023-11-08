<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentInvoiceItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    function resident_invoice_payments(){
      return $this->belongsToMany(ResidentInvoicePayment::class);
    }
}
