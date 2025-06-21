<?php

namespace App\Http\Controllers\API\v2;

use App\BatchMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BatchMessageController extends Controller
{
  public function index(){
    $batch_messages = auth()->user()->batch_messages()->orderBy('created_at', 'desc')->get();
    return response()->json(['data'=>$batch_messages]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'title'     => 'required',
      'body'      => 'required',
      'receivers' => 'required|array',
    ]);

    $batch = BatchMessage::create([
      'admin_id'     => auth()->id(),
      'title'        => $request->title,
      'body'         => $request->body,
      'media_url'    => $this->saveMediaUrl($request),
      'status'       => 'ready'
    ]);

    $batch->receivers()->attach($request->receivers);

    return response()->json(['data' => $batch], 201);
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
    return response()->json([], 204);
  }
}
