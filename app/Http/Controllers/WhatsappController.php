<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Extension;
use App\Jobs\ProcessWhatsapp;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

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

  public function createInstance()
  {
    $response = $this->client->post('createinstance.php', ['query' => [
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c'
    ]]);

    return $response;
  }

  public function getQrCode()
  {
    $response = $this->client->post('getqrcode.php', ['query' => [
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
      'instance_id'  => auth()->user()->whatsapp_instance_id
    ]]);

    return $response;
  }

  public function index()
  {
    $instance_id = auth()->user()->whatsapp_instance_id;

    if ($instance_id == null) {
      $response = $this->createInstance();
      $instance_id = json_decode($response->getBody(), true)['instance_id'];

      $keyUsed = Admin::where('whatsapp_instance_id', $instance_id)->exists();

      if ($keyUsed) {
        Admin::where('whatsapp_instance_id', $instance_id)->update(['whatsapp_instance_id' => null]);
      }
      auth()->user()->update(['whatsapp_instance_id' => $instance_id]);
    }

    if (auth()->user()->whatsapp_status == 'online') {
      $extensions = auth()->user()->extensions()->orderBy('name')->get();
      return view('whatsapp', ['extensions' => $extensions]);
    }

    $response = $this->client->post('setwebhook.php', ['query' => [
      'enable'       => 'true',
      'instance_id'  => $instance_id,
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
      'webhook_url'  => route('whatsapp.hook')
    ]]);

    $response = $this->getQrCode();
    $data = json_decode($response->getBody(), true);
    
    if (array_key_exists('base64', $data)) {
      return view('whatsapp', ['base64' => $data['base64']]);
    }
    return redirect()->route('whatsapp.index');
  }
  
  public function logout(){
      $response = $this->client->post('reboot.php', ['query' => [
        'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
        'instance_id'  => auth()->user()->whatsapp_instance_id
      ]]);
      auth()->user()->update(['whatsapp_status' => 'offline', 'whatsapp_instance_id' => null]);
      return redirect()->route('whatsapp.index');
  }

  public function hook(Request $request)
  {
    if( $request->event == 'state' || $request->event == 'ready' ){
        $data = json_encode($request->all());
        Storage::append('hook.log', $data);
    }
    
    if ($request->event == 'state') {
      Admin::where('whatsapp_instance_id', $request->instance_id)->update(['whatsapp_status' => 'offline', 'whatsapp_instance_id' => null]);
    } else {
      Admin::where('whatsapp_instance_id', $request->instance_id)->update(['whatsapp_status' => 'online']);
    }
    return 'ok';
  }

  public function sendMessage(Request $request)
  {
    $extensions = Extension::whereIn('id', $request->receivers)->get(['id', 'owner_phone', 'phone_1', 'phone_2']);
    $phones = [];
    if ($request->owners_only == 'true') {
      $phones = $extensions->pluck('owner_phone')->toArray();
    } else {
      $phones_1 = $extensions->pluck('phone_1');
      $phones_2 = $extensions->pluck('phone_2');
      $phones = $phones_2->merge($phones_1)->toArray();
    }

    $batchName = "whatsapp-" . auth()->id();
    $batch     = DB::table('job_batches')->whereName($batchName)->first();
    $jobs      = Collection::times(count($phones), function ($i) use ($phones) {
      return new ProcessWhatsapp($phones[$i - 1], 'Test message');
    });

    if ($batch) {
      $batch = Bus::findBatch($batch->id);
      $batch->add($jobs);
    } else {
      Bus::batch($jobs)->name($batchName)->dispatch();
    }

    // foreach($phones as $phone){
    //   ProcessWhatsapp::dispatch($phone, 'prueba de envío de mensajes de whatsapp')->delay( $now );
    //   $now->addSeconds(3);
    // }

    return redirect()->route('whatsapp.index')->with(['message' => 'Mensaje enviado con éxito']);
  }
}
