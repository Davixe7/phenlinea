<?php

namespace App\Http\Controllers\Admin;

use App\WhatsappClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WhatsappClientController extends Controller
{
  public function getClient(){
    return response()->json(['data' => WhatsappClient::where('enabled', true)->first()]);
  }

  public function index(){
    $whatsapp_clients = auth()->user()->whatsapp_clients;
    return view('super.whatsapp-clients', compact('whatsapp_clients'));
  }

  public function update(Request $request, WhatsappClient $whatsapp_client){
    $request->validate([
      'comunity_instance_id'  => 'required',
      'delivery_instance_id'  => 'required',
      'access_token' => 'required',
      'base_url'     => 'required'
    ]);

    if( $whatsapp_client->enabled == false && true ){
      DB::table('whatsapp_clients')->update(['enabled'=>false]);
    }

    $whatsapp_client->update($request->all());
    return redirect()->route('admin.whatsapp_clients.index');
  }
}
