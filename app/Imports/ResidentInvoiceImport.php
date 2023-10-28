<?php

namespace App\Imports;

use App\Extension;
use App\ResidentInvoice;
use App\ResidentInvoiceBatch;
use App\ResidentInvoiceItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ResidentInvoiceImport implements ToCollection, WithHeadingRow
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
      $extension = Extension::where('admin_id', $this->batch->admin_id)
               ->where('name', $row['apto'])
               ->first();

      $invoice = ResidentInvoice::create([
        'apto'         => $row['apto'],
        'resident_invoice_batch_id' => $this->batch->id,
        'extension_id' => $extension->id
      ]);

      for ($i=1; $i < 7; $i++) { 
        if( empty($row["concepto{$i}"]) ) continue;
        ResidentInvoiceItem::create([
          'resident_invoice_id' => $invoice->id,
          'title'   => $row["concepto{$i}"],
          'pending' => $row["vencido{$i}"],
          'current' => $row["actual{$i}"]
        ]);
      }
    }
  }
}
