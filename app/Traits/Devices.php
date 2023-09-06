<?php

namespace App\Traits;

use App\Visit;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Devices
{
  protected $api;

  public function __construct()
  {
    $this->api = new Client(['base_uri' => config('zhyaf.base_url')]);
    $this->getAccessToken();
  }

  public function getAccessToken(){
    return Cache::remember('zhyaf_access_token', 7200, function(){
      try {
        $response = $this->api->get('platCompany/extapi/getAccessToken', [
          'query' => [
            'timeZone'  => 'America/Bogota',
            'language'  => 'es_ES',
            'appId'     => config('zhyaf.app_id'),
            'appSecret' => config('zhyaf.app_secret')
          ]
        ]);
    
        $body = json_decode($response->getBody());
        $data = property_exists($body, 'data') ? $body->data : null;
        $accessToken = ( $data && property_exists($data, 'accessToken') ) ? $data->accessToken : null;
      } catch (GuzzleException $e) {
        return $e->getMessage();
      }
      return $accessToken;
    });
  }

  public function addFacialTempPwd(Visit $visit)
  {
    $path   = $visit->getFirstMediaPath('picture');
    $type   = pathinfo($path, PATHINFO_EXTENSION);
    $data   = file_get_contents($path);
    $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

    $multipart  = [
      ['name' => 'accessToken', 'contents'      => self::getAccessToken()],
      ['name' => 'extCommunityId', 'contents'   => 56714],
      ['name' => 'devSns', 'contents'           => 'V4280618453'],
      ['name' => 'accStartdatetime', 'contents' => $visit->start_date],
      ['name' => 'accEnddatetime', 'contents'   => $visit->end_date],
      ['name' => 'accUsableCount', 'contents'   => 0],
      ['name' => 'name', 'contents'             => $visit->name],
      ['name' => 'phone', 'contents'            => $visit->phone],
      ['name' => 'faceFileBase64', 'contents'   => $base64],
    ];

    try {
      $response    = $this->api->post('visEmpVisitor/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      $success     = property_exists($body, 'code') && $body->code == 0 ? true : false;

      if( !$success ){ return; }
      
      $visit->update(['password' => $body->data->tempPwd]);
      $qrcode = QrCode::format('png')->size(270)->generate( $body->data->tempCode );
      $visit->addMediaFromBase64( base64_encode($qrcode) )->toMediaCollection('qrcode');
    } catch (GuzzleException $e) {
      return $e->getMessage();
    }
  }
  
  public function addTempPwd(Visit $visit)
  {
    $multipart = [
      ['name' => 'accessToken', 'contents'    => $this->getAccessToken()],
      ['name' => 'extCommunityId', 'contents' => 56714],
      ['name' => 'startDate', 'contents'      => $visit->start_date],
      ['name' => 'endDate', 'contents'        => $visit->end_date],
      ['name' => 'usableCount', 'contents'    => 0],
      ['name' => 'devSns', 'contents'         => 'V' . $visit->admin->device_serial_number]
    ];

    Storage::append('devices.log', json_encode($multipart));

    try {
      $response    = $this->api->post('accVisitorTempPwd/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      Storage::append('devices.log', json_encode($body->data->tempPwd));

      if( !property_exists($body, 'code') || $body->code != 0 ){ return; }

      $visit->addMediaFromBase64( $body->data->qrCode )->toMediaCollection('qrcode');
      $visit->update(['password' => $body->data->tempPwd]);
    } catch (GuzzleException $e) {
      return $e->getMessage();
    }
  }

}
