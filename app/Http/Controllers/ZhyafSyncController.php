<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Resources\ZhyafResidents;
use App\Traits\Devices;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ZhyafSyncController extends Controller
{
    public function createCommunity(Admin $admin, Request $request){
        return $admin;
        $api = new Devices($admin);
        try {
            $data = $this->api->createCommunity($admin);
            return response()->json([
                'success' => true,
                'message' => 'Comunidad sincronizada con exito',
                'data'    => $data,
            ], 200);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'No se pudo sincronizar la comunidad',
                'error'   => [],
            ], 400);
        }
    }

    public function createUnit(Admin $admin, Request $request){
        try {
            $data = $this->api->createUnit($admin);
            return response()->json([
                'success' => true,
                'message' => 'Edificio sincronizado con exito',
                'data'    => $data,
            ], 200);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'No se pudo sincronizar el edificio',
                'error'   => [],
            ], 400);
        }
    }
}