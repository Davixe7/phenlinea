<?php

namespace App\Imports;

use App\ResidentInvoice;
use App\ResidentInvoiceBatch;
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
      ResidentInvoice::create([
        'resident_invoice_batch_id' => $this->batch->id,

        'apto'      => $row['apto'],

        'concepto1'          => $row['concepto1'],
        'vencido1'           => $row['vencido1'],
        'actual1'            => $row['actual1'],
        'ultimo_pago1'       => $row['ultimo_pago1'],
        'fecha_ultimo_pago1' => $row['fecha_ultimo_pago1'],

        'concepto2' => $row['concepto2'], 'vencido2' => $row['vencido2'], 'actual2' => $row['actual2'], 'ultimo_pago2' => $row['ultimo_pago2'], 'fecha_ultimo_pago2' => $row['fecha_ultimo_pago2'],
        'concepto3' => $row['concepto3'], 'vencido3' => $row['vencido3'], 'actual3' => $row['actual3'], 'ultimo_pago3' => $row['ultimo_pago3'], 'fecha_ultimo_pago3' => $row['fecha_ultimo_pago3'],
        'concepto4' => $row['concepto4'], 'vencido4' => $row['vencido4'], 'actual4' => $row['actual4'], 'ultimo_pago4' => $row['ultimo_pago4'], 'fecha_ultimo_pago4' => $row['fecha_ultimo_pago4'],
        'concepto5' => $row['concepto5'], 'vencido5' => $row['vencido5'], 'actual5' => $row['actual5'], 'ultimo_pago5' => $row['ultimo_pago5'], 'fecha_ultimo_pago5' => $row['fecha_ultimo_pago5'],
        'concepto6' => $row['concepto6'], 'vencido6' => $row['vencido6'], 'actual6' => $row['actual6'], 'ultimo_pago6' => $row['ultimo_pago6'], 'fecha_ultimo_pago6' => $row['fecha_ultimo_pago6'],
      ]);
    }
  }
}
