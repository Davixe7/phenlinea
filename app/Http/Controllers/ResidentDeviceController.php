<?php

namespace App\Http\Controllers;

use App\Resident;
use App\Traits\Devices;
use Illuminate\Http\Request;

class ResidentDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Resident $resident)
    {
        $api = new Devices();
        $communityDevices = $api->getUnitDevices();
        $householdDevices = $api->getHouseholdDevices($resident);
        $householdDevSns  = $householdDevices->pluck('devSn');
        return collect( $communityDevices )->map(function($dev) use ($householdDevSns) {
        $dev['auth'] = $householdDevSns->contains($dev['devSn']) ? 1 : 0;
        return $dev;
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Resident $resident)
    {
        $request->validate(['devSns'=>'required']);
        $api = new Devices();
        return $api->addDeviceAuth($resident, $request->devSns);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Resident $resident)
    {
        $request->validate(['devSns'=>'required']);
        $api = new Devices();
        return $api->deleteDeviceAuth($resident, $request->devSns);
    }
}
