<?php

namespace App\Imports;

use App\Extension;
use App\Admin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\Rule;

class ExtensionsImport implements ToModel, WithBatchInserts, WithChunkReading, SkipsOnFailure, WithValidation
{
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function model(array $row)
  {
    $name = $row[0];
    
    if( Admin::find( request()->admin_id )->extensions()->name($name)->first() ){
      return null;
    }
    
    return new Extension([
      "name"     => $row[0],
      "phone_1"  => $row[1],
      "phone_2"  => $row[2],
      "admin_id" => request()->admin_id,
    ]);
  }
  
  public function batchSize():int{
    return 1;
  }

  public function chunkSize():int{
    return 1;
  }
  
  public function onFailure(Failure ...$failures)
  {
    foreach( $failures as $f ){
      $string = 'Error en la fila '. $f->row() . ' ' . $f->errors()[0];
      session()->push( 'validation.errors', $string );
    }
  }
  
  public function onError(\Throwable $exception)
  {
    foreach( $exception as $e){
      var_dump( $e );
    }
    throw $exception("Error Processing Request", 1);
  }
  
  public function rules():array{
    return [
      "0" => function ($att, $value, $fail) {
        if( $value != '' && Admin::find( request()->admin_id )->extensions()->name($value)->first() ){
          $fail('Extension duplicada de nombre ' . $value);
        }
      },
      "1" => "required|digits:10",
      "2" => "nullable|digits:10",
    ];
  }
}
