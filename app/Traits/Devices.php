<?php

namespace App\Traits;

use App\Admin;
use App\Porteria;
use App\Visit;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Devices
{
  public $user;
  protected $api;
  protected $zhyafAppId;
  protected $zhyafAppSecret;
  protected $zhyafApiVersion;
  protected $zhyafBaseUri;
  protected $zhyafCacheKey;

  function __construct($user = null)
  {
    $this->user = $user;
    $this->api = new Client([
      'base_uri' => config('zhyaf.v1.base_url'),
      'headers'  => [
        'language' => 'es_ES',
        'timeZone' => 'America/Bogota'
      ]
    ]);
    $this->getAccessToken();
  }

  protected function setData()
  {
    $user = auth()->user();
    if ($user instanceof Porteria) {
      $this->user = auth()->user()->admin;
    }
    if ($user instanceof Admin) {
      $this->user = auth()->user();
    }

    $this->zhyafApiVersion = $this->user->device_api_version;
    $this->zhyafAppId      = config("zhyaf.$this->zhyafApiVersion.app_id");
    $this->zhyafAppSecret  = config("zhyaf.$this->zhyafApiVersion.app_secret");
    $this->zhyafBaseUri    = config("zhyaf.$this->zhyafApiVersion.base_url");
    $this->zhyafCacheKey   = "zhyaf_access_token_$this->user->device_api_version";
  }

  function getAccessToken()
  {
    $this->setData();

    return Cache::remember($this->zhyafCacheKey, 7200, function () {
      try {
        $response = $this->api->get('platCompany/extapi/getAccessToken', [
          'multipart' => [
            ['name' => 'timeZone',    'contents'  => 'America/Bogota'],
            ['name' => 'language',    'contents'  => 'es_ES'],
            ['name' => 'appId',       'contents'  => $this->zhyafAppId],
            ['name' => 'appSecret',   'contents'  => $this->zhyafAppSecret]
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

  function fetchZhyaf($endpoint, $query)
  {
    $multipart = [
      ['name' => 'accessToken',      'contents' => $this->getAccessToken()],
      ['name' => 'extCommunityUuid', 'contents' => $this->user->id],
      ['name' => 'language', 'contents' => 'es_ES'],
    ];

    foreach ($query as $key => $value) {
      $multipart[] = ['name' => $key, 'contents' => $value];
    }

    try {
      $response = $this->api->post($endpoint, compact('multipart'));
      $body     = json_decode($response->getBody());
      $code     = property_exists($body, 'code') ? $body->code : null;

      Log::info( 'Zhyaf' . $response->getBody() );

      if (is_null($code)) {
        throw new Exception("Zhyaf request failed without error code", 522);
      }

      if ($code != 0) {
        throw new Exception($body->msg . " " . $this->user->name, $code);
      }

      return property_exists($body, 'data') ? $body->data : $body->msg;
    } catch (Exception $e) {
      Log::error("FetchZhyaf Exception: " . $e->getMessage());
      throw $e;
    }
  }

  //Rooms
  function addRoom($extension)
  {
    $query = [
      'buildingUuid'     => $extension->admin_id,
      'uuid'             => $extension->id,
      'name'             => $extension->name,
      'code'             => $extension->name,
    ];

    return $this->fetchZhyaf('sqRoom/extapi/add', $query);
  }

  function deleteRoom($extension)
  {
    $query = [
      'uuids'            => $extension->id,
      'isDeleteEmp'      => 1
    ];

    return $this->fetchZhyaf('sqRoom/extapi/delete', $query);
  }

  //Residents
  function addResident($resident, $picturePath)
  {
    try {
      $query = [
        'uuid'             => $resident->id,
        'name'             => $resident->name,
        'phone'            => $resident->email,
        'cardNos'          => $resident->card,
        'roomUuids'        => $resident->extension_id
      ];

      if ($picturePath) {
        $base64 = base64_encode(file_get_contents($picturePath));
        $query['faceFileBase64Array'] = $base64;
      }

      $response = $this->fetchZhyaf('persEmpHousehold/extapi/add', $query);
      return $response;
    }
    catch (Exception $e) {
      throw $e;
    }
  }

  function updateResident($resident, $picturePath)
  {
    $query = [
      'uuid'             => $resident->id,
      'name'             => $resident->name,
      'phone'            => $resident->email,
      'cardNos'          => $resident->tags
    ];

    if ($picturePath) {
      $base64 = base64_encode(file_get_contents($picturePath));
      $query['faceFileBase64Array'] = $base64;
    }

    return $this->fetchZhyaf('persEmpHousehold/extapi/update', $query);
  }

  function deleteResident($resident)
  {
    $query = [
      'uuids'            => $resident->id,
      'extCommunityUuid' => auth()->id()
    ];
    return $this->fetchZhyaf('persEmpHousehold/extapi/delete', $query);
  }

  function getHouseholdDevices($resident)
  {
    $query = [
      'uuid' => $resident->id,
      'extCommunityUuid' => $resident->admin_id
    ];

    try {
      $data = $this->fetchZhyaf('persEmpHousehold/extapi/getAuthorizationDevList', $query);
      if ($data && is_array($data)) {
        $devices = collect($data);
        return $devices->map(function ($dev) {
          return [
            'devName' => $dev->devName,
            'devSn' => $dev->devSn,
          ];
        });
      }

      return collect([]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  function addDeviceAuth($resident, $devSn)
  {
    $query = [
      'uuids'  => $resident->id,
      'devSns' => $devSn
    ];

    try {
      $data = $this->fetchZhyaf('persEmpHousehold/extapi/designatedDeviceAuthByDevSn', $query);
      return $data;
    } catch (Exception $e) {
      throw $e;
    }
  }

  function deleteDeviceAuth($resident, $devSn)
  {
    $query = [
      'extCommunityUuid' => auth()->id(),
      'uuids'  => $resident->id,
      'devSns' => $devSn
    ];

    try {
      $data = $this->fetchZhyaf('persEmpHousehold/extapi/delEmpInfoByDevSn', $query);
      return $data;
    } catch (Exception $e) {
      throw $e;
    }
  }

  //Visits
  function addFacialTempPwd(Visit $visit, $base64 = null)
  {
    $query  = [
      'devSns'              => 'V' . $visit->admin->device_serial_number,
      'accStartdatetime'    => $visit->start_date,
      'accEnddatetime'      => $visit->end_date,
      'accUsableCount'      => 2,
      'name'                => $visit->visitor->name,
      'phone'               => $visit->visitor->phone,
      'uuid'                => $visit->visitor->id,
      'faceFileBase64'      => $base64 ?: $visit->visitor->getFaceFileBase64() 
    ];

    try {
      $data     = $this->fetchZhyaf('visEmpVisitor/extapi/add', $query);
      Log::info(json_encode($data));
      $tempPwd  = property_exists($data, 'tempPwd')  ? $data->tempPwd : '';
      $tempCode = property_exists($data, 'tempCode') ? $data->tempCode : '';
      $qrcode   = QrCode::format('png')->size(270)->generate($tempCode);
      $visit->addMediaFromBase64(base64_encode($qrcode))->usingFileName(Str::random() . '.png')->toMediaCollection('qrcode');
      $visit->update(['password' => $tempPwd]);
    } catch (Exception $e) {
      Log::error('Facial visit: ' . json_encode($data));
      Log::error('Error al registrar la visita facial ' . $e->getMessage());
      throw $e;
    }
  }

  function addTempPwd(Visit $visit)
  {
    $query = [
      'devSns'      => 'V' . $visit->admin->device_serial_number,
      'startDate'   => $visit->start_date,
      'endDate'     => $visit->end_date,
      'usableCount' => 2,
    ];

    try {
      $data = $this->fetchZhyaf('accVisitorTempPwd/extapi/add', $query);
      $tempCode = property_exists($data, 'tempCode') ? $data->tempCode : '';
      $tempPwd  = property_exists($data, 'tempPwd') ? $data->tempPwd : '';
      $mediaFileName = Str::random() . '.png';

      $visit->update(['password' => $tempPwd]);

      $qrCode = property_exists($data, 'qrCode')
        ? $data->qrCode
        : QrCode::format('png')->size(270)->generate($tempCode);

      $visit
        ->addMediaFromBase64(base64_encode($qrCode))
        ->usingFileName($mediaFileName)
        ->toMediaCollection('qrcode');

      return $data;
    } catch (Exception $e) {
      Log::info('visit');
      throw $e;
    }
  }

  //Admin
  function getUnitDevices()
  {
    $data = $this->fetchZhyaf('devDevice/extapi/list', []);

    if (!property_exists($data, 'list') || !is_array($data->list)) {
      return ([]);
    }

    $devices = collect($data->list);
    return $devices->map(fn ($dev) => [
      'devName' => property_exists($dev, 'positionFullName') ? $dev->positionFullName : 'Undefined',
      'devSn'   => $dev->devSn,
    ]);
  }

  function getAccessLogs()
  {
    return Cache::remember('accessLogs' . auth()->id(), 300, function () {
      try {
        $query = [
          'extCommunityUuid' => auth()->id(),
          'pageSize' => request()->has('pageSize') ? request()->pageSize : 1000
        ];

        if (request()->has('currPage')) {
          $query['currPage'] = request()->currPage;
        }

        $data = $this->fetchZhyaf('normalOpenDoorlog/extapi/list', $query);
        return $data;
      } catch (Exception $e) {
        throw $e;
      }
    });
  }

  function getDoors()
  {
    $data = $this->fetchZhyaf('sqDoor/extapi/list', []);
    if (property_exists($data, 'list') && is_array($data->list)) {
      return collect($data->list);
    }
    return collect([]);
  }

  function updateDoor($door, $isCommonDoor = null)
  {
    $query = [
      'id'               => $door->id,
      'positionType'     => 0,
      'isCommonDoor'     => !is_null($isCommonDoor) ? $isCommonDoor : $door->isCommonDoor,
      'name'             => $door->name,
    ];

    $this->fetchZhyaf('sqDoor/extapi/update', $query);
  }

  function grantAccessToAllDoors($resident)
  {
    $devSns = $this->getUnitDevices($resident->extension->admin_id)->pluck('devSn')->toArray();
    $devSns = implode(",", $devSns);
    $this->addDeviceAuth($resident, $devSns);
  }

  //Root
  function syncDoors()
  {
    $admins = Admin::whereNotNull('device_community_id')
      ->whereNotNull('device_building_id')
      ->where('device_api_version', 'v1')->get();

    $admins->each(function ($admin) {
      $this->user = $admin;

      $this
        ->getDoors($admin->id)
        ->each(fn($door) => $this->updateDoor($door, '0'));

      $residentIds = $admin->residents()->pluck('residents.id')->toArray();
      $devSns      = $this->getUnitDevices($admin->id)->pluck('devSn')->toArray();
      $devSns      = implode(",", $devSns);

      if (count($residentIds) <= 999) {
        $this->addDeviceAuth($devSns, implode(',', $residentIds));
      } else {
        $resIdsChunks = array_chunk($residentIds, 990);
        foreach ($resIdsChunks as $chunk) {
          $this->addDeviceAuth($devSns, implode(',', $chunk));
        }
      }
    });
  }

  function exportRooms($admin)
  {
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

      if ($code == null) {
      }
      if ($code != 0) {
      }

      return $response->getBody();
    } catch (Exception $e) {
      throw $e;
    }
  }
}
