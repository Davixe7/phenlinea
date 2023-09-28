<?php
namespace App\Imports;

use App\Extension;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DevicesImport implements WithHeadingRow, ToCollection
{
    public function collection(Collection $rows){
      foreach($rows as $row){
        DB::table('extensions')
        ->where('admin_id', 356)
        ->whereNull('device_room_id')
        ->where('name', str_pad( $row['room_name'], 4, '0', STR_PAD_LEFT ))
        ->update(['device_room_id' => $row['id']]);
      }
    }
}