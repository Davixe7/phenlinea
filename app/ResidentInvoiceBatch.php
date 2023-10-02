<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentInvoiceBatch extends Model
{
    use HasFactory;
    protected $guarded = [];

    function resident_invoices(){
      return $this->hasMany(ResidentInvoice::class);
    }

    function admin(){
      return $this->belongsTo(Admin::class);
    }
}
