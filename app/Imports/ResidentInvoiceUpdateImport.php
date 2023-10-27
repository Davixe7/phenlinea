<?php

namespace App\Imports;

use App\ResidentInvoice;
use App\ResidentInvoiceBatch;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
      ResidentInvoice::whereApto($row['apto'])
      ->whereResidentInvoiceBatchId($this->batch->id)->update([
        'ultimo_pago1'       => $row['ultimo_pago1'],
        'fecha_ultimo_pago1' => Carbon::parse($row['fecha_ultimo_pago1']),

        'ultimo_pago2'       => $row['ultimo_pago2'],
        'fecha_ultimo_pago2' => Carbon::parse($row['fecha_ultimo_pago2']),

        'ultimo_pago3'       => $row['ultimo_pago3'],
        'fecha_ultimo_pago3' => Carbon::parse($row['fecha_ultimo_pago3']),

        'ultimo_pago4'       => $row['ultimo_pago4'],
        'fecha_ultimo_pago4' => Carbon::parse($row['fecha_ultimo_pago4']),

        'ultimo_pago5'       => $row['ultimo_pago5'],
        'fecha_ultimo_pago5' => Carbon::parse($row['fecha_ultimo_pago5']),

        'ultimo_pago6'       => $row['ultimo_pago6'],
        'fecha_ultimo_pago6' => Carbon::parse($row['fecha_ultimo_pago6']),
      ]);
    }
  }
}
