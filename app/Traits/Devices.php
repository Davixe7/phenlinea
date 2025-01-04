<?php

namespace App\Traits;

use App\Admin;
use App\Visit;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Devices
{
  protected $api;
  protected $user;

  function __construct($user = null)
  {
    $this->user = $user;
    $this->api = new Client([
      'base_uri' => config('zhyaf.v1.base_url'),
      'headers'  => [
        'language' => 'en_ES',
        'timeZone' => 'America/Bogota'
      ]
    ]);
    $this->getAccessToken();
  }

  function getAccessToken()
  {
    if(!$this->user){
      $this->user = auth()->user()->admin ?: auth()->user();
    }
    $data = config('zhyaf.' . $this->user->device_api_version);

    return Cache::remember('zhyaf_access_token_' . $this->user->device_api_version, 7200, function () use ($data) {
      try {
        $response = $this->api->get('platCompany/extapi/getAccessToken', [
          'multipart' => [
            ['name' => 'timeZone',    'contents'  => 'America/Bogota'],
            ['name' => 'language',    'contents'  => 'es_ES'],
            ['name' => 'appId',       'contents'  => $data['app_id']],
            ['name' => 'appSecret',   'contents'  => $data['app_secret']]
          ]
        ]);

        $body = json_decode($response->getBody());

        $data = property_exists($body, 'data') ? $body->data : null;
        $accessToken = ($data && property_exists($data, 'accessToken')) ? $data->accessToken : null;
        return $accessToken;
      } catch (GuzzleException $e) {
        Storage::append('devices.log', $e->getMessage());
        return null;
      }
    });
  }

  function fetchZhyaf($endpoint, $query){
    $uuid = auth()->user()->admin_id ?: auth()->id();

    $multipart = [
      ['name' => 'accessToken',      'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityUuid', 'contents' => $uuid],
      ['name' => 'language', 'contents' => 'es_ES'],
    ];

    foreach($query as $key => $value){
      $multipart[] = ['name' => $key, 'contents' => $value];
    }

    try {
      $response = $this->api->post($endpoint, compact('multipart'));
      $body     = json_decode( $response->getBody() );
      $code     = property_exists( $body, 'code' ) ? $body->code : null;

      if( is_null( $code ) ){
        throw new Exception("Zhyaf request failed without error code", 522);
      }

      if( $code != 0 ){
        throw new Exception($body->msg, $code);
      }

      return property_exists($body, 'data') ? $body->data : $body->msg;
    }
    catch(Exception $e){
      Storage::append('zhyaf.error.log', $endpoint . " " . $e->getCode() . " " . $e->getMessage() );
      throw $e;
    }
  }

  function addRoom($extension){
    $query = [
      'buildingUuid'     => $extension->admin_id,
      'uuid'             => $extension->id,
      'name'             => $extension->name,
      'code'             => $extension->name,
    ];

    return $this->fetchZhyaf('sqRoom/extapi/add', $query);
  }

  function deleteRoom($extension){
    $query = [
      'uuids'            => $extension->id,
      'isDeleteEmp'      => 1
    ];

    return $this->fetchZhyaf('sqRoom/extapi/delete', $query);
  }

  function addResident($resident, $picturePath){
    $query = [
      'uuid'             => $resident->id,
      'name'             => $resident->name,
      'phone'            => $resident->email,
      'cardNos'          => $resident->card,
      'roomUuids'        => $resident->extension_id
    ];

    if( $picturePath ){
      $base64 = base64_encode(file_get_contents($picturePath));
      $query['faceFileBase64Array'] = $base64;
    }

    return $this->fetchZhyaf('persEmpHousehold/extapi/add', $query);
  }

  function updateResident($resident, $picturePath){
    $query = [
      'uuid'             => $resident->id,
      'name'             => $resident->name,
      'phone'            => $resident->email,
      'cardNos'          => $resident->tags
    ];

    if( $picturePath ){
      $base64 = base64_encode(file_get_contents($picturePath));
      $query['faceFileBase64Array'] = $base64;
    }

    return $this->fetchZhyaf('persEmpHousehold/extapi/update', $query);
  }

  function deleteResident($resident){
    $query = [
      'uuids'            => $resident->id,
      'extCommunityUuid' => auth()->id()
    ];
    return $this->fetchZhyaf('persEmpHousehold/extapi/delete', $query);
  }

  function addFacialTempPwd(Visit $visit){
    $query  = [
      'devSns'              => 'V'.$visit->admin->device_serial_number,
      'accStartdatetime'    => $visit->start_date,
      'accEnddatetime'      => $visit->end_date,
      'accUsableCount'      => 1,
      'name'                => $visit->visitor->name,
      'phone'               => $visit->visitor->phone,
      'faceFileBase64'      => $visit->visitor->getFaceFileBase64()
    ];

    try {
      $data     = $this->fetchZhyaf('visEmpVisitor/extapi/add', $query);
      $tempPwd  = property_exists($data, 'tempPwd')  ? $data->tempPwd : '';
      $tempCode = property_exists($data, 'tempCode') ? $data->tempCode : '';
      $qrcode   = QrCode::format('png')->size(270)->generate($tempCode);
      $visit->addMediaFromBase64(base64_encode($qrcode))->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
      $visit->update(['password' => $tempPwd]);
    }
    catch(Exception $e){
      throw $e;
    }
  }

  function addTempPwd(Visit $visit){
    $query = [
      'devSns'      => 'V' . $visit->admin->device_serial_number,
      'startDate'   => $visit->start_date,
      'endDate'     => $visit->end_date,
      'usableCount' => 1,
    ];

    try {
      $data = $this->fetchZhyaf('accVisitorTempPwd/extapi/add', $query);
      Storage::append('visits.log', json_encode($data));
      if( property_exists($data, 'qrCode') ){
        $visit->addMediaFromBase64($data->qrCode)->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
      }
      $visit->update(['password' => $data->tempPwd]);
      return $data;
    }
    catch( Exception $e ){
      throw $e;
    }
  }

  function restoreEmails(Admin $admin){
    $residents = $admin->residents()->whereNotNull('residents.email')->get();
    foreach( $residents as $resident ){
      try {
        $this->updateResident($resident, null);
        Storage::append('zhyaf.residents.log', $resident->id . " updated");
      }
      catch(Exception $e){
        Storage::append('zhyaf.residents.log', $resident->id . " " . $e->getMessage());
      }
    }
  }

  function importFaces($admin){
    $data = ['multipart' => [
      ['name' => 'accessToken', 'contents' => $this->getAccessToken()],
      ['name' => 'timeZone',       'contents'  => 'America/Bogota'],
      ['name' => 'language',       'contents'  => 'es_ES'],
      ['name' => 'extCommunityId', 'contents'  => 57972],
      ['name' => 'pageSize',       'contents'  => 1205],
      ['name' => 'isHaveFace',     'contents'  => 1],
      ['name' => 'currPage',       'contents'  => 1]
    ]];

    try {
      $response = $this->api->get('persEmpHousehold/extapi/list', $data);
      $body     = json_decode( $response->getBody() );
      $code     = property_exists( $body, 'code' ) ? $body->code : null;

      if( is_null($code) ){
        Storage::append('import-faces-error.log');
      }

      if( $code == 0 ){
        $count = 0;
        $extensions = $admin->extensions;

        foreach( $body->data->list as $resident){
          if( !property_exists($resident, 'roomName') ){
            Storage::append($admin->id ."-422-import-faces-error.log", "$resident->name $resident->id roomName not present");
            continue;
          }
          else {
            $roomName  = explode("Paseo sevilla/", $resident->roomName)[1];
            $roomName  = explode('|', $roomName)[0];
            $extension = $extensions->where('name', $roomName)->first();
          }
          if( !$extension ){
            Storage::append($admin->id ."-422-import-faces-error.log", "Extension not found $roomName");
            continue;
          }

          if( property_exists($resident, 'uuid') && $resident->uuid){
            $phResident = $extension->residents()->find($resident->uuid);
          }
          else if( property_exists($resident, 'id') && $resident->id ){
            $phResident = $extension->residents()->where('device_resident_id', $resident->id)->first();
          }
          else {
            $residentName = trim($resident->name);
            $residentName = str_replace("  ", " ", $residentName);
            $residentName = explode(" ", $residentName)[0];
            $phResident = $extension
                          ->residents()
                          ->where('name', 'LIKE', "%" . $residentName . "%")
                          ->first();
          }

          if( !$phResident ){
            Storage::append($admin->id . "-404-import-faces-error.log", "$resident->name $resident->id $resident->roomName $extension->name" );
            continue;
          }

          if( $phResident->hasMedia('picture') ){
            Storage::append($admin->id . "-200-import-faces-error.log", "200 $resident->name $resident->roomName $extension->name" );
            continue;
          }

          else {
            $phResident->update(['device_resident_id' => $resident->id]);
            if(is_array($resident->images)){
              $phResident->addMediaFromUrl($resident->images[0])->toMediaCollection('picture');
            }
            $count++;
            Storage::append($admin->id . '-import-faces-error.log', "$count updated" );
          }
        }
      }

      if( $code != 0 ){
        Storage::append('import-faces-error.log', $code . " " , $body->msg);
      }
    }catch( Exception $e ){
      Storage::append('import-faces-error.log', $e->getMessage());
    }

  }

  function dropRooms($admin){
    $ids   = $admin->extensions()->pluck('id')->implode(',');

    $query = [
      'accessToken'      => $this->getAccessToken(),
      'extCommunityUuid' => $admin->id,
      'uuids'            => $ids,
      'isDeleteEmp'      => 1
    ];
    
    try{
      $response = $this->api->get('persEmpHousehold/extapi/delete', compact('query'));
      $body     = json_decode($response->getBody());
      $code     = property_exists($body, 'code') ?: null;

      if($code != null){
        return $response->getBody();
      }
      
    }catch( Exception $e ){
      throw $e;
    }
  }

  function exportRooms($admin){
    $extensions  = $admin->extensions()->get([
      'id as uuid',
      'name',
      'name as code',
      'admin_id as buildingUuid',
    ]);

    $json        = ["RoomList" => $extensions];
    $accessToken = $this->getAccessToken();
    
    try {
      $response = $this->api->get("sqRoom/extapi/saveBatchRooms/?accessToken={$accessToken}&extCommunityUuid={$admin->id}", compact('json'));
      $body     = json_decode($response->getBody());
      $code     = property_exists($body, 'code') ?: null;

      if($code == null){}
      if($code != 0){}

      return $response->getBody();
    }
    catch( Exception $e ){
      throw $e;
    }
  }

  function getUnitDevices($adminId = null){
    $query = [
      'extCommunityUuid' => $adminId ?: auth()->id() 
    ];

    try {
      $data = $this->fetchZhyaf('devDevice/extapi/list', $query);
      Storage::append('visits.log', json_encode($data));
      if( property_exists($data, 'list') && is_array($data->list) ){
        $devices = collect( $data->list );
        return $devices->map(function($dev){
          return [
            'devName' => $dev->positionFullName,
            'devSn' => $dev->devSn,
          ];
        });
      }
      return ([]);
    }
    catch( Exception $e ){
      throw $e;
    }
  }

  function getHouseholdDevices($resident){
    $query = [
      'uuid' => $resident->id,
      'extCommunityUuid' => $resident->admin_id
    ];

    try {
      $data = $this->fetchZhyaf('persEmpHousehold/extapi/getAuthorizationDevList', $query);
      if( $data && is_array($data) ){
        $devices = collect( $data );
        return $devices->map(function($dev){
          return [
            'devName' => $dev->devName,
            'devSn' => $dev->devSn,
          ];
        });
      }

      return collect([]);
    }
    catch( Exception $e ){
      throw $e;
    }
  }

  function addDeviceAuth($devSn, $residentId){
    $query = [
      'extCommunityUuid' => auth()->id(),
      'uuids'  => $residentId,
      'devSns' => $devSn
    ];

    try {
      $data = $this->fetchZhyaf('persEmpHousehold/extapi/designatedDeviceAuthByDevSn', $query);
      return $data;
    }
    catch( Exception $e ){
      throw $e;
    }
  }

  function deleteDeviceAuth($devSn, $residentId){
    $query = [
      'extCommunityUuid' => auth()->id(),
      'uuids'  => $residentId,
      'devSns' => $devSn
    ];

    try {
      $data = $this->fetchZhyaf('persEmpHousehold/extapi/delEmpInfoByDevSn', $query);
      return $data;
    }
    catch( Exception $e ){
      throw $e;
    }
  }

  function getAccessLogs(){
    return Cache::remember('accessLogs'.auth()->id(), 300, function(){
      try {
        $query = [
          'extCommunityUuid' => auth()->id(),
          'pageSize' => request()->has('pageSize') ? request()->pageSize : 1000
        ];
    
        if( request()->has('currPage') ){
          $query['currPage'] = request()->currPage;
        }

        $data = $this->fetchZhyaf('normalOpenDoorlog/extapi/list', $query);
        return $data;
      }
      catch( Exception $e ){
        throw $e;
      }
    });
  }

  function getDoors($adminId = null){
    $query = [];
    if( $adminId ){ $query['extCommunityUuid'] = $adminId; }
    $data = $this->fetchZhyaf('sqDoor/extapi/list', $query);
    if (property_exists($data, 'list') && is_array($data->list)) {
      return collect($data->list);
    }
    return collect([]);
  }

  function updateDoor($door, $isCommonDoor = null){
    $query = [
      'id'           => $door->id,
      'isCommonDoor' => !is_null($isCommonDoor) ?: $door->isCommonDoor,
      'name'         => $door->name,
    ];

    $this->fetchZhyaf('sqDoor/extapi/update', $query);
  }

  function syncDoors(){
    $admins = Admin::whereNotNull('device_community_id')->get();

    $admins->each(function($admin){
      $doors       = $this->getDoors($admin->id);
      $residentIds = $admin->residents()->pluck('residents.id')->toArray();
      $residentIds = implode(",", $residentIds);
      $devSns      = $this->getUnitDevices($admin->id)->pluck('devSn')->toArray();
      $devSns      = implode(",", $devSns);

      $doors->each(function($door){
        //$this->updateDoor($door, 0);
      });
      
      $this->addDeviceAuth($devSns, $residentIds);
    });
  }
}
