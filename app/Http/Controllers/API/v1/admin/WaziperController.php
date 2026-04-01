<?php

namespace App\Http\Controllers\API\v1\admin;

use App\Http\Controllers\Controller;
use App\Services\WaziperService;
use App\WhatsappClient;
use Exception;
use Illuminate\Http\Request;

class WaziperController extends Controller
{
    protected $api;

    public function __construct()
    {
        $client = WhatsappClient::whereStatus(1)->first();
        $this->api = new WaziperService($client);
    }

    public function create_instance(){
        try {
            $instance_id = $this->api->createInstance();
            return $instance_id;
        }
        catch (Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function get_qrcode(Request $request){
        try {
            $qrcode = $this->api->getQrcode($request->instance_id);
            return $qrcode;
        }
        catch (Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
