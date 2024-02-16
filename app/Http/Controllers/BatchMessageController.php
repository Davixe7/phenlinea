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

  public function __construct()
  {
    $this->whatsapp = new Whatsapp(WhatsappClient::find(1));
  }

  public function index(){
    $batch_messages = auth()->user()->batch_messages;
    $statuses       = [
      'pending'    => 'Pendiente',
      'ready'      => 'En cola',
      'processing' => 'En proceso',
      'sent'       => 'Enviado',
      'taken'      => 'Enviado',
      'failed'     => 'Fallido',
    ];
    return view('admin.whatsapp.index', compact('batch_messages', 'statuses'));
  }
  
  public function create(Request $request){
    $instance_id = auth()->user()->whatsapp_instance_id;
    if( $instance_id && !$this->whatsapp->validateInstance($instance_id, auth()->user()->phone)){
      $this->whatsapp->logout($instance_id);
      auth()->user()->update(['whatsapp_instance_id'=>null]);
    }

    $message = auth()->user()->batch_messages()->whereIn('status', ['pending', 'failed'])->latest()->first();
    if( $message && !$request->filled('pending_adviced') ){
      return view('admin.whatsapp.pending', compact('message'));
    }

    $extensions  = auth()->user()->extensions()->get(['admin_id', 'extensions.id', 'extensions.name', 'phone_1', 'owner_phone']);
    $instance_id = auth()->user()->whatsapp_instance_id ?: null;
    $phone       = auth()->user()->phone;
    return view('admin.whatsapp.create', compact('extensions', 'instance_id', 'phone', 'message'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'receivers' => 'required|array',
      'body'      => 'required',
      'title'     => 'required'
    ]);

    $aptos  = Extension::whereIn('id', $request->receivers)->get(['id', 'owner_phone', 'phone_1', 'phone_2']);

    $phones = $request->owners_only
              ? $aptos->pluck('owner_phone')
              : $aptos->pluck('phone_1')->merge($aptos->pluck('phone_2'));

    $phones = $phones
              ->filter(fn ($phone) => ($phone && ($phone[0] == '3')))
              ->toArray();

    if (!count($phones)) abort(422, 'Not valid phones were found');

    $batch = BatchMessage::create([
      'admin_id'     => auth()->id(),
      'numbers'      => implode(',', $phones),
      'title'        => $request->title,
      'body'         => $this->formatMessage($request->body),
      'media_url'    => $this->saveMediaUrl($request),
      'status'       => auth()->user()->hasValidInstance() ? 'ready' : 'pending'
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
