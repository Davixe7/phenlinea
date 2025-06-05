<?php

namespace App\Traits\Zhyaf;

use Exception;
use Illuminate\Support\Facades\Cache;

trait ZhyafAdmin
{
    use InteractsWithZhyaf;

    function zhyafExportRooms()
    {
        $extensions  = $this->extensions()->get([
            'id as uuid',
            'name',
            'name as code',
            'admin_id as buildingUuid',
        ]);
        $json     = ["RoomList" => $extensions];
        //$response = $this->api->get("sqRoom/extapi/saveBatchRooms/?accessToken={$accessToken}&extCommunityUuid={$this->id}", compact('json'));
        //return $response->getBody();
    }

    function zhyafGetUnitDevices()
    {
        $data = $this->fetchZhyaf('devDevice/extapi/list', []);

        if (!property_exists($data, 'list') || !is_array($data->list)) {
            return ([]);
        }

        $devices = collect($data->list);
        return $devices->map(function ($dev) {
            return [
                'devName' => property_exists($dev, 'positionFullName') ? $dev->positionFullName : 'Undefined',
                'devSn'   => $dev->devSn,
            ];
        });
    }

    function getAccessLogs()
    {
        return Cache::remember('accessLogs' . $this->id, 300, function () {
            $query = [
                'pageSize' => request()->has('pageSize') ? request()->pageSize : 1000
            ];

            if (request()->has('currPage')) {
                $query['currPage'] = request()->currPage;
            }

            $data = $this->fetchZhyaf('normalOpenDoorlog/extapi/list', $query);
            return $data;
        });
    }
}
