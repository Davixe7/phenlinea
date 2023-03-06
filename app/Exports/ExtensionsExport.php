<?php 

namespace App\Exports;

use App\Extension;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExtensionsExport implements FromCollection
{
    public function collection()
    {
      $admin = request()->admin ?: auth()->user(); 
      $exts = $admin->extensions()->get(['name', 'phone_1', 'phone_2', 'admin_id']);
      return $exts;
    }
}

?>