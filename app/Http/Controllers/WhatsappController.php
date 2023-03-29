<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Extension;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WhatsappController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  private $client;
  private $query;

  public function __construct()
  {
    $this->query = ['access_token' => '3f8b18194536bdafa301c662dc9caa4c'];
    $this->client = new Client(['base_uri' => 'https://asistbot.com/api/']);
  }

  //Instance ID Invalidated
  public function createInstance()
  {
    $query   = ['access_token' => '3f8b18194536bdafa301c662dc9caa4c'];
    $headers = [['accept'=>'application/json']];
    $response    = $this->client->post('createinstance.php', compact('query', 'headers'));
    $instance_id = json_decode($response->getBody(), true)['instance_id'];
    return $instance_id;
  }

  public function getQrCode($instance_id)
  {
    $this->query['instance_id'] = $instance_id;
    $response = $this->client->post('getqrcode.php', ['query' => $this->query]);
    $data = json_decode($response->getBody(), true);

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
    $response = $this->client->get('http://api.phenlinea.com/api/batches/?user_id=' . auth()->id()); 
    $history  = json_decode($response->getBody())->data;
    $mode = 'comunity';
    return view('admin.whatsapp.comunity', compact('history', 'mode'));
  }

  public function index()
  {
    if (auth()->user()->whatsapp_status == 'online') {
      $extensions = auth()->user()->extensions()->orderBy('name')->get();

      $response = $this->client->get('http://api.phenlinea.com/api/batches/?user_id=' . auth()->id()); 
      $history  = json_decode($response->getBody())->data;
      $whatsapp_instance_id = auth()->user()->whatsapp_instance_id;

      return view('admin.whatsapp.index', compact('extensions', 'history', 'whatsapp_instance_id'));
    }

    $instance_id = $this->createInstance();
    $this->setWebHook($instance_id);
    $qrcode_src  = $this->getQrCode($instance_id);

    return view('admin.whatsapp.index', compact('instance_id', 'qrcode_src'));
  }

  public function setWebHook($instance_id)
  {
    $this->client->post('setwebhook.php', ['query' => [
      'enable'       => 'true',
      'instance_id'  => $instance_id,
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
      'webhook_url'  => route('whatsapp.hook')
    ]]);
  }

  public function hook(Request $request)
  {
    $data    = json_encode($request->all());
    $arrData = json_decode($data);

    Storage::append('whatsapp_hook.log', json_encode($arrData));

    if ($arrData->event == 'ready') {
      $phone = explode(':', $arrData->data->id)[0];

      Admin::where('phone', $phone)->update([
        'whatsapp_status'      => 'online',
        'whatsapp_instance_id' => $arrData->instance_id
      ]);
      Storage::append('whatsapp_hook.log', json_encode($arrData));
      return 1;
    }

    if ($arrData->event == 'logout') {
      Admin::where('instance_id', $arrData->instance_id)->update([
        'whatsapp_status'      => 'offline',
        'whatsapp_instance_id' => null
      ]);
    }
  }

  public function logout()
  {
    $response = $this->client->post('reboot.php', ['query' => [
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
      'instance_id'  => auth()->user()->whatsapp_instance_id
    ]]);

    Storage::append('whatsapp_hook.log', auth()->user()->whatsapp_instance_id . " requested logout");

    auth()->user()->update([
      'whatsapp_status'      => 'offline',
      'whatsapp_instance_id' => null
    ]);

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
      'receivers' => 'required|array'
    ]);

    $extensions = Extension::whereIn('id', $request->receivers)->get(['id', 'owner_phone', 'phone_1', 'phone_2']);
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

    // $response = $this->client->post('http://161.35.60.29/api/whatsapp-batches', [
    //   'form_params' => [
    //     'admin_id'              => auth()->id(),
    //     'admin_name'            => auth()->user()->name,
    //     'admin_phone'           => auth()->user()->phone,
    //     'whatsapp_instance_id'  => auth()->user()->whatsapp_instance_id,
    //     'message'               => $request->message,
    //     'receivers'             => $receivers,
    //     'media_url'             => $media_url
    //   ]
    // ]);

   $admin_name = auth()->user()->name;

   $message = "*Unidad: {$admin_name}*\n\n";
   $message = $message . "{$request->message}\n\n";
   $message = $message . "Servicio prestado por PHenlinea.com";

    try {
      $response = $this->client->post('http://api.phenlinea.com/api/batches', [
        'form_params' => [
          'user_id'               => auth()->id(),
          'instance_id'           => auth()->user()->whatsapp_instance_id,
          'message'               => $message,
          'numbers'               => $receivers,
          'media_url'             => $media_url
        ]
      ]);
    }
    catch(ClientException $e){
      return $e->getMessage();
    }

    $response_body = json_encode(json_decode($response->getBody()));
    Storage::append('batchs_messages_response.log', $response_body);
    $response = json_decode($response_body, true);

    return response()->json($response);
  }
}
