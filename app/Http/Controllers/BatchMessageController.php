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
    $this->whatsapp = new Whatsapp($this->client);
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
    $extensions    = auth()->user()->extensions()->get([
      'admin_id',
      'extensions.id',
      'extensions.name',
      'phone_1',
      'owner_phone'
    ]);

    $message = BatchMessage::whereAdminId( auth()->id() )->whereStatus( 'pending' )->first();

    if( $message ){
      return view('admin.whatsapp.pending', compact('message'));
    }

    return view('admin.whatsapp.create', compact('extensions', 'message'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'receivers' => 'required|array',
      'body'      => 'required',
      'title'     => 'required'
    ]);

    $batch = BatchMessage::create([
      'admin_id'     => auth()->id(),
      'title'        => $request->title,
      'body'         => $request->body,
      'media_url'    => $this->saveMediaUrl($request),
      'status'       => 'pending'
    ]);

    $batch->receivers()->attach($request->receivers);

    return response()->json(['data' => $batch]);
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

  public function destroy(BatchMessage $batch_message){
    $batch_message->delete();
    return to_route('batch-messages.index')->with(['message'=>'Mensaje anulado con éxito']);
  }
}
