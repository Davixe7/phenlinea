<?php

namespace App\Http\Controllers;

use App\Admin;
use App\BatchMessage;
use App\Extension;
use App\Traits\Whatsapp;
use App\WhatsappClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BatchMessageController extends Controller
{
  protected $whatsapp;
  protected $client;

  public function __construct()
  {
    $this->client   = WhatsappClient::find(1);
    $this->whatsapp = new Whatsapp();
  }

  public function index(){
    $batch_messages = auth()->user()->batch_messages()->orderBy('created_at', 'desc')->get();
    $statuses       = [
      'pending'    => 'Pendiente',
      'ready'      => 'En cola',
      'processing' => 'En proceso',
      'sent'       => 'Enviado',
      'failed'     => 'Fallido',
    ];
    return view('admin.whatsapp.index', compact('batch_messages', 'statuses'));
  }
  
  public function create(Request $request){
    $instance_id   = $this->client->batch_instance_id;
    $phone         = $this->client->batch_instance_phone;
    $validInstance = $instance_id && $this->whatsapp->validateInstance($instance_id, $phone);
    $message       = auth()->user()->batch_messages()->whereIn('status', ['ready', 'pending', 'failed'])->latest()->first();
    $extensions    = auth()->user()->extensions()->get([
      'admin_id',
      'extensions.id',
      'extensions.name',
      'phone_1',
      'owner_phone'
    ]);

    if( !$validInstance ){
      $this->whatsapp->logout($instance_id);
      auth()->user()->update(['whatsapp_instance_id'=>null]);
    }

    if( $message && !$request->filled('pending_adviced') ){
      return view('admin.whatsapp.pending', compact('message'));
    }

    return view('admin.whatsapp.create', compact('extensions', 'instance_id', 'phone', 'message'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'receivers' => 'required|array',
      'body'      => 'required',
      'title'     => 'required'
    ]);

    $instance_id    = $this->client->batch_instance_id;
    $instance_phone = $this->client->batch_instance_phone;
    $validInstance  = $this->whatsapp->validateInstance($instance_id, $instance_phone);

    $aptos  = Extension::whereIn('id', $request->receivers)->get(['id', 'owner_phone', 'phone_1', 'phone_2']);

    $phones = $request->owners_only
              ? $aptos->pluck('owner_phone')
              : $aptos->pluck('phone_1')->merge($aptos->pluck('phone_2'));

    $phones = $phones
              ->filter(fn ($phone) => ($phone == '4147912134') || ($phone && ($phone[0] == '3')))
              ->toArray();

    if (!count($phones)) abort(422, 'Not valid phones were found');

    $batch = BatchMessage::create([
      'admin_id'     => auth()->id(),
      'numbers'      => implode(',', $phones),
      'title'        => $request->title,
      'body'         => $this->formatMessage($request->body),
      'media_url'    => $this->saveMediaUrl($request),
      'status'       => 'ready'
    ]);

    return response()->json(['data' => $batch]);
  }

  public function formatMessage($body){
    $admin_name = auth()->user()->name;
    $message = "*Unidad: {$admin_name}*\n\n";
    $message = $message . "{$body}\n\n";
    $message = $message . "Servicio prestado por PHenlinea.com";
    return $message;
  }

  public function saveMediaUrl($request){
    if (!$file = $request->file('file')) return null;

    $clearFileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
    $fileName = $clearFileName . time() . "." . $file->extension();
    $path = $file->storeAs('/public/whatsapp_attachments', $fileName);
    $media_url = asset("/storage/whatsapp_attachments/{$fileName}");
    Storage::append('batch_messages_media.log', $path);

    return $media_url;
  }

  public function authenticate(Request $request){
    auth()->user()->update(['whatsapp_instance_id' => $request->instance_id]);
    $message = auth()->user()->batch_messages()->whereStatus('pending')->latest()->first();
    $message->update(['status'=>'ready']);
    return response()->json(['data'=>'success']);
  }

  public function destroy(BatchMessage $batch_message){
    $batch_message->delete();
    return to_route('batch-messages.index')->with(['message'=>'Mensaje anulado con éxito']);
  }

  public function hook(Request $request)
  {
    $data = json_encode($request->all());
    $data = json_decode($data, true);

    Storage::append('whatsapp_hook.log', json_encode($data));

    if ($data['event'] == 'ready') {
      $phone = substr($data['phone'], 2);

      Admin::where('phone', $phone)->update([
        'whatsapp_status'      => 'online',
        'whatsapp_instance_id' => $data['instance_id']
      ]);
    }

    if ($data->event == 'logout') {
      Admin::where('instance_id', $data['instance_id'])->update([
        'whatsapp_status'      => 'offline',
        'whatsapp_instance_id' => null
      ]);
    }
  }
}
