<?php

namespace App\Http\Controllers;

use App\Admin;
use App\BatchMessage;
use App\Extension;
use App\Traits\Whatsapp;
use App\WhatsappClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WhatsappController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  protected $client;
  protected $query;
  protected $api;

  public function __construct()
  {
    $this->client = WhatsappClient::where('enabled', 1)->first();
    $this->api    = new Client(['base_uri' => $this->client['base_url'], 'verify'=>false]);
    $this->query  = ['access_token' => $this->client->access_token];
  }

  public function createInstance()
  {
    $query    = $this->query;
    $headers  = [['accept'=>'application/json']];
    try {
      $response = $this->api->get('create_instance', compact('query', 'headers'));
      $body = json_decode($response->getBody(), 1);
      if( is_array($body) && array_key_exists('instance_id', $body ) ){
        return $body['instance_id'];
      }
    }
    catch(GuzzleException $e){
      return abort('200', $e->getMessage());
    }
  }

  public function getQrCode($instance_id)
  {
    $this->query['instance_id'] = $instance_id;
    $response = $this->api->get('get_qrcode', ['query' => $this->query]);
    $data = json_decode($response->getBody(), 1);

    if (($response->getStatusCode() >= 400) || ($data && ($data['status'] == 'error'))) {
      Storage::append('whatsapp_error.log', 'InstanceID: ' . $instance_id . ' ' . $response->getBody());
      if (request()->expectsJson()) {
        return null;
      }
      return redirect()->route('whatsapp.index');
    }

    if ($data && array_key_exists('base64', $data)) {
      return $data['base64'];
    }

    Storage::append('whatsapp_error.log', 'InstanceID: ' . $instance_id . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() . ' No error message available');
    return null;
  }

  public function getQRurl()
  {
    $instance_id = $this->createInstance();
    return response()->json(['data' => $this->getQrCode($instance_id)]);
  }

  public function comunity(){
    if( !auth()->user()->whatsapp_group_id ){
      return view('admin.whatsapp.disabled');
    }
    $response = $this->api->get('http://api.phenlinea.com/api/batches/', ['query'=>[
      'user_id' => auth()->id(),
      'type'    => 'comunity',
    ]]); 
    $history  = json_decode($response->getBody())->data;
    $mode = 'comunity';
    return view('admin.whatsapp.comunity', compact('history', 'mode'));
  }

  public function login(){
    $whatsapp      = new Whatsapp();
    $instance_id   = $whatsapp->getInstanceId();
    $webook_status = $whatsapp->setWebHook( $instance_id, route('whatsapp.hook') );
    $base64        = $whatsapp->getQrCode( $instance_id );
    return view('admin.whatsapp.login', compact('instance_id', 'base64'));
  }

  public function index()
  {
    if (auth()->user()->whatsapp_status == 'offline') { return to_route('whatsapp.login'); }
    $extensions           = auth()->user()->extensions()->orderBy('name')->get();
    $history              = BatchMessage::whereAdminId( auth()->id() )->get();
    $whatsapp_instance_id = auth()->user()->whatsapp_instance_id;
    return view('admin.whatsapp.index', compact('extensions', 'history', 'whatsapp_instance_id'));
  }

  public function hook(Request $request)
  {
    Storage::append('whatsapp_hook.log', 'incoming data');
    $data    = json_encode($request->all());
    $arrData = json_decode($data, true);

    Storage::append('whatsapp_hook.log', json_encode($arrData));

    if ($arrData['event'] == 'ready') {
      $phone = $arrData['phone'] ?: explode(':', $arrData['data']['id'])[0];

      Admin::where('phone', $phone)->update([
        'whatsapp_status'      => 'online',
        'whatsapp_instance_id' => $arrData['instance_id']
      ]);
      Storage::append('whatsapp_hook.log', json_encode($arrData));
      return 1;
    }

    if ($arrData->event == 'logout') {
      Admin::where('instance_id', $arrData['instance_id'])->update([
        'whatsapp_status'      => 'offline',
        'whatsapp_instance_id' => null
      ]);
    }
  }

  public function logout()
  {
    $whatsapp = new Whatsapp();
    $whatsapp->logout(auth()->user()->whatsapp_instance_id);

    auth()->user()->update(['whatsapp_status' => 'offline', 'whatsapp_instance_id' => null]);
    return redirect()->route('home');
  }

  public function isOnline()
  {
    $data = auth()->user()->whatsapp_status == 'offline' ? 0 : 1;
    return response()->json(['data' => $data]);
  }

  public function sendMessage(Request $request)
  {
    $request->validate([
      'receivers' => 'required|array',
      'message'   => 'required'
    ]);

    $extensions = Extension::whereIn('id', $request->receivers)
                  ->get(['id', 'owner_phone', 'phone_1', 'phone_2']);
    $phones = [];
    if ($request->owners_only == 'true') {
      $phones = $extensions->pluck('owner_phone')->toArray();
    } else {
      $phones_1 = $extensions->pluck('phone_1');
      $phones_2 = $extensions->pluck('phone_2');
      $phones = array_filter($phones_2->merge($phones_1)->toArray());
    }

    $phones = collect($phones)->filter(function($phone){ return ($phone && ($phone[0] == '3')); })->toArray();
    if (!$phones || !count($phones)) {
      return response()->json(['data'=>'Error'], 422);
    }

    $receivers = implode(',', $phones);

    $path = '';
    $media_url = '';

    if ($file = $request->file('attachment')) {
      $clearFileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
      $fileName = $clearFileName . time() . "." . $file->extension();
      $path = $file->storeAs('/public/whatsapp_attachments', $fileName);
      $media_url = asset("/storage/whatsapp_attachments/{$fileName}");
      Storage::append('batch_messages_media.log', $path);
    }

   $admin_name = auth()->user()->name;

   $message = "*Unidad: {$admin_name}*\n\n";
   $message = $message . "{$request->message}\n\n";
   $message = $message . "Servicio prestado por PHenlinea.com";

   $batch = BatchMessage::create([
    'admin_id'     => auth()->id(),
    'message'      => $message,
    'numbers'      => $receivers,
    'media_url'    => $media_url,
   ]);

    return response()->json(['data'=>$batch]);
  }

  public function sendComunity(Request $request){
    
    $client = WhatsappClient::whereEnabled(1)->firstOrFail();
    $api    = new Whatsapp();

    $media_url = null;

    if( $file = $request->file('attachment') ){
      $path      = $file->store('public/whatsapp_attachments');
      $media_url = asset('storage/' . str_replace('public/', '', $path));
    }

    $message  = $client->getMessage($request->message, auth()->user()->name);

    $batch  = BatchMessage::create([
      'group_id'  => auth()->user()->whatsapp_group_id,
      'admin_id'  => auth()->id(),
      'media_url' => $media_url,
      'message'   => $message,
      'status'    => 'taken',
    ]);

    $response = $api->send(
      $client->comunity_instance_id,
      $batch->group_id,
      $batch->message,
      $batch->media_url,
      $batch->group_id
    );

    return response()->json($response ? $batch : []);
  }
}

