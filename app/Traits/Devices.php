<?php

namespace App\Traits;

use App\Resident;
use App\Visit;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Devices
{
  protected $api;

  public function __construct()
  {
    $this->api = new Client([
      'base_uri' => config('zhyaf.base_url'),
      'headers'  => [
        'language' => 'en_US',
        'timeZone' => 'America/Bogota'
      ]
    ]);
    $this->getAccessToken();
  }

  public function getAccessToken()
  {
    return Cache::remember('zhyaf_access_token', 7200, function () {
      try {
        $response = $this->api->get('platCompany/extapi/getAccessToken', [
          'multipart' => [
            ['name' => 'timeZone', 'contents'  => 'America/Bogota'],
            ['name' => 'language', 'contents'  => 'es_ES'],
            ['name' => 'appId', 'contents'     => config('zhyaf.app_id')],
            ['name' => 'appSecret', 'contents' => config('zhyaf.app_secret')]
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

  function addRoom($extension){
    $multipart = [
      ['name' => 'accessToken',    'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId', 'contents' => $extension->admin->device_community_id],
      ['name' => 'buildingId',     'contents' => $extension->admin->device_building_id],
      ['name' => 'uuid',           'contents' => $extension->id],
      ['name' => 'name',           'contents' => $extension->name],
      ['name' => 'code',           'contents' => $extension->name],
    ];

    try {
      $response    = $this->api->post('sqRoom/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      $code        = property_exists($body, 'code') ? $body->code : null;

      if( is_null($code) ){
        return false;
      }

      if( $code == 0 ){
        $extension->update(['device_room_id' => $body->data->id, 'device_synced' => 1]);
        return true;
      }

      if( $code != 0 ){
        Storage::append('rooms.log', $body->code . ' ' . $body->msg);  
        return false;
      }
    } catch (GuzzleException $e) {
      Storage::append('rooms.log', $e->getMessage());
      return false;
    }
  }

  function addResident($resident, $picturePath){
    $multipart = [
      ['name' => 'accessToken',        'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId',     'contents' => $resident->extension->admin->device_community_id],
      ['name' => 'uuid',               'contents' => $resident->id],
      ['name' => 'name',               'contents' => $resident->name],
      ['name' => 'phone',              'contents' => $resident->email],
      ['name' => 'cardNos',            'contents' => $resident->card],
    ];

    $multipart[] = $resident->extension->device_room_id
                    ? ['name' => 'roomIds',   'contents' => $resident->extension->device_room_id]
                    : ['name' => 'roomUuids', 'contents' => $resident->extension->id];

    if( $picturePath ){
      $base64 = base64_encode(file_get_contents($picturePath));
      $multipart[] = ['name' => 'faceFileBase64Array', 'contents' => $base64];
    }

    try {
      $response    = $this->api->post('persEmpHousehold/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      $code        = property_exists($body, 'code') ? $body->code : null;

      if( $code == 0 ){
        $resident->update(['device_resident_id' => $body->data->id]);
        return true;
      }

      if( is_null( $code ) ){
        Storage::append('residents.log', json_encode( $body ));
        return false;
      }

      Storage::append('residents.log', $body->code . ' ' . $body->msg);
      return false;

    } catch (GuzzleException $e) {
      Storage::append('residents.log', $e->getMessage());
      return false;
    }
  }

  function updateResident($resident, $picturePath){
    $id = $resident->device_resident_id;

    $multipart = [
      ['name' => 'accessToken',       'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId',    'contents' => $resident->extension->admin->device_community_id],
      ['name' => $id ? 'id' : 'uuid', 'contents' => $id ?: $resident->id],
      ['name' => 'name',              'contents' => $resident->name],
      ['name' => 'phone',             'contents' => $resident->email],
      ['name' => 'cardNos',           'contents' => $resident->tags]
    ];

    if( $picturePath ){
      $base64 = base64_encode(file_get_contents($picturePath));
      $multipart[] = ['name' => 'faceFileBase64Array', 'contents' => $base64];
    }

    try {
      $response    = $this->api->post('persEmpHousehold/extapi/update', compact('multipart'));
      $body        = json_decode($response->getBody());
      $code        = property_exists($body, 'code') ? $body->code : null;

      if( is_null($code) ){
        Storage::append('residents.log', 'No hay código de error disponible' . json_encode($body));
        return false;
      }

      if ( $code == 0 ){
        $resident->update(['device_synced'=>true]);
        return true;
      }

      return false;
      
    } catch (GuzzleException $e) {
      Storage::append('residents.log', $e->getMessage());
      return false;
    }
  }

  function deleteResident($resident){
    $id = $resident->device_resident_id;
    $multipart = [
      ['name' => 'accessToken',         'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId',      'contents' => $resident->extension->admin->device_community_id],
      ['name' => $id ? 'ids' : 'uuids', 'contents' => $id ?: $resident->id],
    ];

    try {
      $response    = $this->api->post('persEmpHousehold/extapi/delete', compact('multipart'));
      $body        = json_decode($response->getBody());
      $code        = property_exists($body, 'code') ? $body->code : null;

      if ( $code == 0 ) {
        return true;
      }

      if( is_null( $code ) ){
        return false;
      }

    } catch (GuzzleException $e) {
      Storage::append('residents.error.log', $e->getMessage());
      return false;
    }
  }

  public function addFacialTempPwd(Visit $visit)
  {
    $base64 = $visit->visitor->getFaceFileBase64();

    $multipart  = [
      ['name' => 'accessToken', 'contents'      => self::getAccessToken()],
      ['name' => 'extCommunityId', 'contents'   => $visit->admin->device_community_id],
      ['name' => 'devSns', 'contents'           => 'V'.$visit->admin->device_serial_number],
      ['name' => 'accStartdatetime', 'contents' => $visit->start_date],
      ['name' => 'accEnddatetime', 'contents'   => $visit->end_date],
      ['name' => 'accUsableCount', 'contents'   => 1],
      ['name' => 'name', 'contents'             => $visit->visitor->name],
      ['name' => 'phone', 'contents'            => $visit->visitor->phone],
      ['name' => 'faceFileBase64', 'contents'   => $base64]
    ];

    try {
      $response    = $this->api->post('visEmpVisitor/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());

      $codeZero = property_exists($body, 'code') && $body->code == 0 ? true : false;
      $tempPwd  = property_exists($body->data, 'tempPwd')  ? $body->data->tempPwd : '';
      $tempCode = property_exists($body->data, 'tempCode') ? $body->data->tempCode : '';

      if (!$codeZero || !$tempPwd || !$tempCode) {
        Storage::append('visitors.log', now() . ' ' . json_encode($body));
        return $visit;
      }

      $visit->update(['password' => $tempPwd]);
      $qrcode = QrCode::format('png')->size(270)->generate($tempCode);
      $visit->addMediaFromBase64(base64_encode($qrcode))->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
      return $visit;
    } catch (GuzzleException $e) {
      Storage::append('visitors.log', $e->getMessage());
    }

    if( $visit->admin->device_2_serial_number ){
      $multipart[4]['contents'] = $visit->end_date;
      try {
        $response    = $this->api->post('visEmpVisitor/extapi/add', compact('multipart'));
        $body        = json_decode($response->getBody());
        $success     = property_exists($body, 'code') && $body->code == 0 ? true : false;
  
        if (!$success) {
          return;
        }
  
        $visit->update(['password' => $body->data->tempPwd]);
        $qrcode = QrCode::format('png')->size(270)->generate($body->data->tempCode);
        $visit->addMediaFromBase64(base64_encode($qrcode))->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
        return $visit;
      } catch (GuzzleException $e) {
        Storage::append('devices.log', $e->getMessage());
      }
    }
  }

  public function addTempPwd(Visit $visit)
  {
    $multipart = [
      ['name' => 'accessToken', 'contents'    => $this->getAccessToken()],
      ['name' => 'extCommunityId', 'contents' => $visit->admin->device_community_id],
      ['name' => 'startDate', 'contents'      => $visit->start_date],
      ['name' => 'endDate', 'contents'        => $visit->end_date],
      ['name' => 'usableCount', 'contents'    => 1],
      ['name' => 'devSns', 'contents'         => 'V' . $visit->admin->device_serial_number]
    ];

    try {
      $response    = $this->api->post('accVisitorTempPwd/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      $success     = property_exists($body, 'code') && $body->code == 0;

      if (!$success) {
        Storage::append('visitors.log', now() . ' ' . json_encode($body));
        return $visit;
      }

      $visit->addMediaFromBase64($body->data->qrCode)->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
      $visit->update(['password' => $body->data->tempPwd]);
      return $visit;
    } catch (GuzzleException $e) {
      Storage::append('visitors.log', $e->getMessage());
      return $visit;
    }
  }
}
