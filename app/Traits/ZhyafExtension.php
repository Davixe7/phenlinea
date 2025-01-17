<?php

namespace App\Traits\Zhyaf;

trait ZhyafExtension
{
    use InteractsWithZhyaf;
    
    function addRoom()
    {
        $query = [
            'buildingUuid'     => $this->getExtCommunityUuid(),
            'uuid'             => $this->id,
            'name'             => $this->name,
            'code'             => $this->name,
        ];

        return $this->fetchZhyaf('sqRoom/extapi/add', $query);
    }

    function deleteRoom()
    {
        $query = [
            'uuids'       => $this->id,
            'isDeleteEmp' => 1
        ];

        return $this->fetchZhyaf('sqRoom/extapi/delete', $query);
    }
}
