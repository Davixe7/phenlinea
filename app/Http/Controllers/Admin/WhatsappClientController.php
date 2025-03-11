<?php

namespace App\Http\Controllers\Admin;

use App\WhatsappClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Whatsapp;
use Illuminate\Support\Facades\DB;

class WhatsappClientController extends Controller
{
  public function getClient(){
    return response()->json(['data' => WhatsappClient::where('enabled', true)->first()]);
  }

  public function index(){
    $whatsapp_clients = WhatsappClient::all();
    return view('super.whatsappclients.index', compact('whatsapp_clients'));
  }

  public function scan(Request $request, WhatsappClient $whatsapp_client){
    $whatsapp      = new Whatsapp();

    $labels = [
      'delivery_instance_id' => 'Encomiendas',
      'batch_instance_id'    => 'Masívos',
      'comunity_instance_id' => 'Comunidad',
    ];

    $base64        = null;
    $pairing_code  = null;
    $instance_type = $request->instance_type;
    $instance_id   = $whatsapp->getInstanceId();
    $whatsapp->setWebhook($instance_id, 'https://phenlinea.com/whatsapp/hook');
    
    if( $request->scanMethod == 'pairingCode' ){
      $data = $whatsapp->getPairingCode( '57' . $whatsapp_client->batch_instance_phone );
      $pairing_code = $data->pairingCode;
      $instance_id  = $data->instance_id;
    }else {
      $base64       = $whatsapp->getQrCode( $instance_id );
    }

    return view('super.whatsappclients.scan', compact(
      'pairing_code',
      'whatsapp_client',
      'instance_id',
      'base64',
      'instance_type',
      'labels'
    ));
  }

  public function update(Request $request, WhatsappClient $whatsapp_client){
    // $request->validate([
    //   'comunity_instance_id'  => 'required',
    //   'access_token'          => 'required',
    //   'base_url'              => 'required'
    // ]);

    if( $whatsapp_client->enabled == false && $request->enabled ){
      DB::table('whatsapp_clients')->update(['enabled'=>false]);
    }

    $whatsapp_client->update($request->all());
    return redirect()->route('admin.whatsapp_clients.index');
  }
}
