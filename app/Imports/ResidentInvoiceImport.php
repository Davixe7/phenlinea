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
      foreach($rows as $row){
        ResidentInvoice::create([
          'resident_invoice_batch_id' => $this->batch->id,

          'apto'      => $row['apto'],
          'concepto1' => $row['concepto1'],
          'vencido1'  => $row['vencido1'],
          'actual1'   => $row['actual1'],
      
          'concepto2' => $row['concepto2'],
          'vencido2'  => $row['vencido2'],
          'actual2'   => $row['actual2'],
      
          'concepto3' => $row['concepto3'],
          'vencido3'  => $row['vencido3'],
          'actual3'   => $row['actual3'],
        ]);
      }
    }
}
