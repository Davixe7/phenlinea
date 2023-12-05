<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Resources\ResidentExport;
use App\Resident;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ZhyafController extends Controller
{
  function exportRooms(){
    $extensions  = Admin::find(356)->extensions()->get(['id as uuid', 'admin_id as buildingUuid', 'name', 'name as code']);

    $data        = ["RoomList" => $extensions];
    $http        = new Client(['base_uri' => 'https://cloud.zhyaf.com:8790']);
    $accessToken = "702d5a9857c292b7ee4f37232a33c9fee2859f453c0061eae3d6f0e62b76b77a0f01e78777025a5dfd2f73e48bb07eca-p37911";
    
    try {
      $response = $http->get("sqRoom/extapi/saveBatchRooms/?accessToken={$accessToken}&extCommunityId=57702", [
        "json" => $data
      ]);
      return $response->getBody();
    }
    catch( Exception $e ){
      return $e->getMessage();
    }
  }

  function exportResidents(){
    $residents  = ResidentExport::collection( auth()->user()->residents )->toArray(true);
    return (new FastExcel( $residents ))->download("residents_" . time() . ".xlsx");
  }

  function exportMedia(){
    $extensions  = Admin::find(356)->extensions()->pluck('id');
    $residents   = Resident::whereIn('extension_id', $extensions)->pluck('id');
    $medias      = Media::where('model_type', 'App\Resident')->whereIn('model_id', $residents)->get();

    $userMedia   = $medias->map(fn($m)=>['phone'=>$m->model_id, 'path'=>$m->getPath()]);
    
    foreach( $userMedia as $media ){
      $extension = pathinfo($media['path'], PATHINFO_EXTENSION);
      $extension = $extension ?: 'png';
      $newPath   = storage_path('batch/' . $media['phone'] . '.' . $extension);
      
      if( !copy( $media['path'], $newPath ) ){
        Storage::append('files.log', 'Unable to copy file ' . $newPath);
      }
    }
    return 'success';
  }
}
