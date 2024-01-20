<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Resources\ResidentExport;
use App\Resident;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZipArchive;

class ZhyafController extends Controller
{
  function exportRooms(){
    $extensions  = Admin::find(184)->extensions()->get([
      'id as uuid',
      'admin_id as buildingUuid',
      'name',
      'name as code'
    ]);

    $data        = ["RoomList" => $extensions];
    $http        = new Client(['base_uri' => 'https://cloud.zhyaf.com:8790']);
    $accessToken = "dfe2671c4c6775ab38ddb4eb7d73eccc4a35a6e6842612d86cf0348182a604240f01e78777025a5dfd2f73e48bb07eca-p37911";
    
    try {
      $response = $http->get("sqRoom/extapi/saveBatchRooms/?accessToken={$accessToken}&extCommunityId=59884", [
        "json" => $data
      ]);
      return $response->getBody();
    }
    catch( Exception $e ){
      return $e->getMessage();
    }
  }

  function exportResidents(){
    $residents  = ResidentExport::collection( Admin::find(184)->residents )->toArray(true);
    return (new FastExcel( $residents ))->download("residents_" . time() . ".xlsx");
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
