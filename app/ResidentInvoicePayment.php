<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentInvoicePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function resident_invoice(){
      return $this->belongsTo(ResidentInvoice::class);
    }

    public function resident_invoice_items(){
      return $this->belongsToMany(ResidentInvoiceItem::class)->withPivot('amount');
    }

    public function getExtensionAttribute(){
      return $this->resident_invoice->extension;
    }

    public function getPreviousPaymentsAttribute(){
      return ResidentInvoicePayment
             ::where('resident_invoice_id', $this->resident_invoice_id)
             ->where('id', '<', [$this->id])
             ->get();
    }

    public function getPreviousBalanceAttribute(){
      $total = $this->previous_payments->reduce(function (?int $carry, $item) {
        return $carry + $item->amount;
      });

      return $this->resident_invoice->total - $total;
    }
}
