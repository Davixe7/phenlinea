<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Extension;
use App\Jobs\ProcessWhatsapp;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\WhatsappMessagesBatch;
use Illuminate\Support\Str;

class WhatsappController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  private $client;

  public function __construct()
  {
    $this->client = new Client(['base_uri' => 'https://asistbot.com/api/']);
  }
  
  //Instance ID Invalidated
  
  public function createInstance(){
    $response    = $this->client->post('createinstance.php', ['query' => ['access_token' => '3f8b18194536bdafa301c662dc9caa4c']]);
    $instance_id = json_decode( $response->getBody(), true )['instance_id'];
    return $instance_id;
  }
  
  public function getQrCode($instance_id){
    $response = $this->client->post('getqrcode.php', ['query' => ['access_token' => '3f8b18194536bdafa301c662dc9caa4c','instance_id'  => $instance_id]]);
    $data = json_decode( $response->getBody(), true );
    
    if( $data && $data['status'] == 'error' ){
      Storage::append('whatsapp_error.log', 'InstanceID: ' . $instance_id . ' ' . $response->getBody() );
      if( !request()->expectsJson() ){
        return redirect()->route('whatsapp.index');
      }
      return null;
    }
    
    if (array_key_exists('base64', $data)) {
      return $data['base64'];
    }

    Storage::append('whatsapp_error.log', $data['message'] );
    return null;
  }
  
  public function getQRurl(){
    $instance_id = $this->createInstance();
    return response()->json(['data' => $this->getQrCode( $instance_id )]);
  }

  public function index()
  {
    if (auth()->user()->whatsapp_status == 'online') {
      $extensions = auth()->user()->extensions()->orderBy('name')->get();
      $history    = auth()->user()->whatsapp_messages_batches;
      return view('whatsapp', compact('extensions', 'history'));
    }
    
    $instance_id = $this->createInstance();
    $this->setWebHook( $instance_id );
    $qrcode_src  = $this->getQrCode( $instance_id );
    
    return view('whatsapp', compact('instance_id', 'qrcode_src'));
  }
  
  public function setWebHook($instance_id){
    $this->client->post('setwebhook.php', ['query' => [
      'enable'       => 'true',
      'instance_id'  => $instance_id,
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
      'webhook_url'  => route('whatsapp.hook')
    ]]);
  }
  
  public function hook(Request $request)
  {
    $data    = json_encode( $request->all() );
    $arrData = json_decode( $data );
    
    if( $arrData->event == 'ready' ){
      $phone = explode(':', $arrData->data->id)[0];
      
      Admin::where('phone', $phone)->update([
        'whatsapp_status'      => 'online',
        'whatsapp_instance_id' => $arrData->instance_id
      ]);
      Storage::append('whatsapp_hook.log', json_encode($arrData));
      return 1;
    }
    
    if( $arrData->event == 'logout' ){
        Admin::where('instance_id', $arrData->instance_id)->update([
            'whatsapp_status'      => 'offline',
            'whatsapp_instance_id' => null
        ]);
    }
    
  }
  
  public function logout(){
      $response = $this->client->post('reboot.php', ['query' => [
        'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
        'instance_id'  => auth()->user()->whatsapp_instance_id
      ]]);
      return redirect()->route('home');
  }
  
  public function isOnline(){
      $data = auth()->user()->whatsapp_status == 'offline' ? 0 : 1;
      return response()->json(['data'=>$data]);
  }
  
  public function sendMessage(Request $request)
  {
    $request->validate([
        'receivers' => 'required|array'
    ]);
    
    $extensions = Extension::whereIn('id', $request->receivers)->get(['id', 'owner_phone', 'phone_1', 'phone_2']);
    $phones = [];
    if ($request->owners_only == 'true') {
      $phones = $extensions->pluck('owner_phone')->toArray();
    } else {
      $phones_1 = $extensions->pluck('phone_1');
      $phones_2 = $extensions->pluck('phone_2');
      $phones = $phones_2->merge($phones_1)->toArray();
    }

    if( !$phones || !count($phones) ){ return; }

    $batch = WhatsappMessagesBatch::create([
      'admin_id'          => auth()->id(),
      'message'           => $request->message,
      'receivers_numbers' => implode(',', $phones)
    ]);
    
    if( $file = $request->file('attachment') ){
        $fileName = "{$file->getClientOriginalName()}" . now() . ".{$file->extension()}";
        $path = $file->storeAs('whatsapp_attachments', $fileName);
        $batch->addMedia( storage_path( "app/{$path}" ) )->toMediaCollection('attachment');
    }

    return redirect()->route('whatsapp.index')->with(['message'=>'Mensaje enviado exitosamente']);
  }
}
