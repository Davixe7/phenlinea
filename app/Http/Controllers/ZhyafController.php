<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Resources\ZhyafResidents;
use App\Traits\Devices;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZipArchive;

class ZhyafController extends Controller
{
  function restoreEmails(Admin $admin){
    $devices = new Devices();
    $devices->restoreEmails($admin);
    return 'success';
  }
  
  function dropRooms(Admin $admin){
    $devices = new Devices();
    $devices->dropRooms($admin);
    return 'success';
  }

  function exportRooms(Admin $admin){
    $devices = new Devices();
    $devices->exportRooms($admin);
    return 'success';
  }

  function exportResidents(Admin $admin){
    $residents  = ZhyafResidents::collection( $admin->residents )->toArray(true);
    return (new FastExcel( $residents ))->download("{$admin->id}_residents_" . time() . ".xlsx");
  }

  function exportMedia(Admin $admin){
    $residents  = $admin->residents()->pluck('residents.id');
    $medias     = Media::where('model_type', 'App\Resident')->whereIn('model_id', $residents)->get();
    $medias     = $medias->map(fn($m)=>['phone'=>$m->model_id, 'path'=>$m->getPath()]);

    $zip        = new ZipArchive();
    $zipPath    = storage_path("{$admin->id}_" . now()->format('YmdHis') . "_faces.zip");
    $zip->open($zipPath, $zip::CREATE);
    
    foreach( $medias as $media ){
      $extension = pathinfo($media['path'], PATHINFO_EXTENSION);
      $extension = ".$extension";
      $output    = str_replace($extension, "", $media['path']) . ".jpg";
      $filename  = $media['phone'] . $extension;

      exec("mogrify {$media['path']} -format jpg {$media['path']}");

      $zip->addFile($output, $filename);
    }
    $zip->close();
    return 'success';
  }
}
