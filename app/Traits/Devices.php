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
        Storage::append('devices.log', 'getting access_token');
        $response = $this->api->get('platCompany/extapi/getAccessToken', [
          'multipart' => [
            ['name' => 'timeZone', 'contents'  => 'America/Bogota'],
            ['name' => 'language', 'contents'  => 'es_ES'],
            ['name' => 'appId', 'contents'     => config('zhyaf.app_id')],
            ['name' => 'appSecret', 'contents' => config('zhyaf.app_secret')]
          ]
        ]);

        $body = json_decode($response->getBody());

        // $api = new \GuzzleHttp\Client();
        // $response = $api->get('https://cloud.zhyaf.com:8790/platCompany/extapi/getAccessToken', [ 'multipart' => [ ['name' => 'timeZone', 'contents' => 'America/Bogota'], ['name' => 'language', 'contents' => 'es_ES'], ['name' => 'appId', 'contents' => '017dc2a938fc4088a96776313c2bca05'], ['name' => 'appSecret', 'contents' => 'f005f6c7cb22cdd296f466a43c289157'] ] ]);
        // $body = json_decode($response->getBody());

        $data = property_exists($body, 'data') ? $body->data : null;
        $accessToken = ($data && property_exists($data, 'accessToken')) ? $data->accessToken : null;
        Storage::append('devices.log', 'ACCESS_TOKEN: ' . $accessToken);
        return $accessToken;
      } catch (GuzzleException $e) {
        Storage::append('devices.log', $e->getMessage());
        return null;
      }
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
      ['name' => 'extCommunityId', 'contents'   => $visit->admin->device_community_id],
      ['name' => 'devSns', 'contents'           => 'V'.$visit->admin->device_serial_number],
      ['name' => 'accStartdatetime', 'contents' => $visit->start_date],
      ['name' => 'accEnddatetime', 'contents'   => $visit->end_date],
      ['name' => 'accUsableCount', 'contents'   => 1],
      ['name' => 'name', 'contents'             => $visit->name],
      ['name' => 'phone', 'contents'            => $visit->phone],
      ['name' => 'faceFileBase64', 'contents'   => $base64],
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
      return $e->getMessage();
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

    Storage::append('devices.log', json_encode($multipart));

    try {
      $response    = $this->api->post('accVisitorTempPwd/extapi/add', compact('multipart'));
      $body        = json_decode($response->getBody());
      Storage::append('devices.log', json_encode($body));

      if (!property_exists($body, 'code') || $body->code != 0) {
        return;
      }

      $visit->addMediaFromBase64($body->data->qrCode)->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
      $visit->update(['password' => $body->data->tempPwd]);
    } catch (GuzzleException $e) {
      return $e->getMessage();
    }
  }
}
