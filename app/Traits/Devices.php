<?php

namespace App\Traits;

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
    $this->api = new Client(['base_uri' => config('zhyaf.base_url')]);
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

  public function addFacialTempPwd(Visit $visit)
  {
    $path   = $visit->visitor->getFirstMediaPath('picture');
    $type   = pathinfo($path, PATHINFO_EXTENSION);
    $data   = file_get_contents($path);
    $base64 = 'data:application/' . 'png' . ';base64,' . base64_encode($data);

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
      $success     = property_exists($body, 'code') && $body->code == 0 ? true : false;

      if (!$success) {
        return;
      }

      $visit->update(['password' => $body->data->tempPwd]);
      $qrcode = QrCode::format('png')->size(270)->generate($body->data->tempCode);
      $visit->addMediaFromBase64(base64_encode($qrcode))->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
    } catch (GuzzleException $e) {
      Storage::append('devices.log', $e->getMessage());
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

      if (!property_exists($body, 'code') || $body->code != 0) {
        return;
      }

      $visit->addMediaFromBase64($body->data->qrCode)->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
      $visit->update(['password' => $body->data->tempPwd]);
    } catch (GuzzleException $e) {
      return $e->getMessage();
    }
  }

  function addRoom($extension){
    $multipart = [
      ['name' => 'accessToken',    'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId', 'contents' => $extension->admin->device_community_id],
      ['name' => 'buildingId',     'contents' => $extension->admin->device_building_id],
      ['name' => 'name',           'contents' => $extension->name],
      ['name' => 'code',           'contents' => $extension->name],
    ];

    try {
      $response    = $this->api->post('sqRoom/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      if (!property_exists($body, 'code') || $body->code != 0) { return;}
      $extension->update(['device_room_id' => $body->data->id]);
    } catch (GuzzleException $e) {
      return Storage::append('rooms.log', $e->getMessage());
    }
  }

  function addResident($resident){
    $base64 = $resident->getFirstMediaPath('picture')
      ? base64_encode(file_get_contents($resident->getFirstMediaPath('picture')))
      : null;

    $multipart = [
      ['name' => 'accessToken',        'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId',     'contents' => $resident->extension->admin->device_community_id],
      ['name' => 'name',               'contents' => $resident->name],
      ['name' => 'phone',              'contents' => $resident->email],
      ['name' => 'cardNos',            'contents' => $resident->card],
      ['name' => 'roomIds',            'contents' => $resident->extension->device_room_id],
    ];

    if( $base64 ){
     $multipart[] = ['name' => 'faceFileBase64Array', 'contents' => $base64];
    }

    try {
      $response    = $this->api->post('persEmpHousehold/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      if (!property_exists($body, 'code') || $body->code != 0) { return;}
      $resident->update(['device_resident_id' => $body->data->id]);
    } catch (GuzzleException $e) {
      return Storage::append('rooms.log', $e->getMessage());
    }
  }

  function updateResident($resident){
    $base64 = $resident->getFirstMediaPath('picture')
      ? base64_encode(file_get_contents($resident->getFirstMediaPath('picture')))
      : null;

    $multipart = [
      ['name' => 'accessToken',        'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId',     'contents' => $resident->extension->admin->device_community_id],
      ['name' => 'id',                 'contents' => $resident->device_resident_id],
      ['name' => 'name',               'contents' => $resident->name],
      ['name' => 'phone',              'contents' => $resident->email],
      ['name' => 'cardNos',            'contents' => $resident->card]
    ];

    $tags = implode(',', $resident->vehicles()->pluck('tag')->toArray());
    $multipart[5]['contents'] = $multipart[5]['contents'] . ',' . $tags;

    if( $base64 ){
      $multipart[] = ['name' => 'faceFileBase64Array', 'contents' => $base64];
     }

    try {
      $response    = $this->api->post('persEmpHousehold/extapi/update', compact('multipart'));
      $body        = json_decode($response->getBody());
      if (!property_exists($body, 'code') || $body->code != 0) { return;}
    } catch (GuzzleException $e) {
      return Storage::append('residents.log', $e->getMessage());
    }
  }

  function deleteResident($resident){
    $multipart = [
      ['name' => 'accessToken',        'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityId',     'contents' => $resident->extension->admin->device_community_id],
      ['name' => 'ids',                'contents' => $resident->device_resident_id]
    ];

    try {
      $response    = $this->api->post('persEmpHousehold/extapi/delete', compact('multipart'));
      $body        = json_decode($response->getBody());
      if (!property_exists($body, 'code') || $body->code != 0) { return;}
    } catch (GuzzleException $e) {
      return Storage::append('residents.log', $e->getMessage());
    }
  }
}
