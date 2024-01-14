<?php

namespace App\Imports;

use App\ResidentInvoice;
use App\ResidentInvoiceBatch;
use App\ResidentInvoicePayment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ResidentInvoiceUpdateImport implements ToCollection, WithHeadingRow
{
  private ResidentInvoiceBatch $batch;

  public function __construct(ResidentInvoiceBatch $batch)
  {
    $this->batch = $batch;
  }
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function collection(Collection $rows)
  {
    foreach ($rows as $row) {
      $resident_invoice = ResidentInvoice::where('resident_invoice_batch_id', $this->batch->id)->where('apto', $row['apto'])->first();

      $resident_invoice_payment = ResidentInvoicePayment::firstOrCreate([
        'resident_invoice_id' => $resident_invoice->id,
        'amount'              => $row['ultimo_pago'],
        'date'                => $row['fecha_ultimo_pago']
      ]);

      // \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_ultimo_pago'])

      $items   = $resident_invoice->resident_invoice_items()->with('resident_invoice_payments');
      $balance = $resident_invoice_payment->amount;

      $items->each(function($item) use (&$balance, $resident_invoice_payment){
        Storage::append('balances.log', $balance);
        if( $balance <= 0 ){ return false; }
        $debt  = ($item->current + $item->pending);

        if( $item->resident_invoice_payments->count() ) {
          $paid = $item->resident_invoice_payments->reduce(fn(?float $carry, $payment)=>($carry + $payment->amount));
          if( $paid >= $debt ){ return; }
          $debt  = $debt - $paid;
        }

        $abono   = $balance >= $debt ? $debt : $balance;
        $balance = $balance - $abono;
        $item->resident_invoice_payments()->attach($resident_invoice_payment->id, ['amount'=>$abono]);
      });
    }
  }
}
