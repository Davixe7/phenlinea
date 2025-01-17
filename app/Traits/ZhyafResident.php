<?php

namespace App\Traits\Zhyaf;

trait ZhyafExtension
{
    use InteractsWithZhyaf;

    function zhyafAdd($picturePath)
    {
        $query = [
            'uuid'      => $this->id,
            'name'      => $this->name,
            'phone'     => $this->email,
            'cardNos'   => $this->card,
            'roomUuids' => $this->extension_id
        ];

        if ($picturePath) {
            $base64 = base64_encode(file_get_contents($picturePath));
            $query['faceFileBase64Array'] = $base64;
        }

        return $this->fetchZhyaf('persEmpHousehold/extapi/add', $query);
    }

    function zhyafUpdate($picturePath)
    {
        $query = [
            'uuid'    => $this->id,
            'name'    => $this->name,
            'phone'   => $this->email,
            'cardNos' => $this->tags
        ];

        if ($picturePath) {
            $base64 = base64_encode(file_get_contents($picturePath));
            $query['faceFileBase64Array'] = $base64;
        }

        return $this->fetchZhyaf('persEmpHousehold/extapi/update', $query);
    }

    function zhyafDelete()
    {
        $query = [
            'uuids' => $this->id,
        ];
        return $this->fetchZhyaf('persEmpHousehold/extapi/delete', $query);
    }

    function zhyafAddDeviceAuth($devSn)
    {
        $query = [
            'uuids'  => $this->id,
            'devSns' => $devSn
        ];

        $data = $this->fetchZhyaf('persEmpHousehold/extapi/designatedDeviceAuthByDevSn', $query);
        return $data;
    }

    function zhyafAddAllDevicesAuth()
    {
        $devSns = $this->admin->getUnitDevices()->pluck('devSn')->toArray();
        $devSns = implode(",", $devSns);
        $this->addDeviceAuth($devSns, $this->id);
    }

    function zhyafDeleteDeviceAuth($devSn){
        $query = [
          'uuids'  => $this->id,
          'devSns' => $devSn
        ];
    
        $data = $this->fetchZhyaf('persEmpHousehold/extapi/delEmpInfoByDevSn', $query);
        return $data;
    }
}
