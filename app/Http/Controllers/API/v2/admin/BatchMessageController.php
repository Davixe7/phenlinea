<?php

namespace App\Http\Controllers\API\v2\admin;

use App\BatchMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BatchMessageController extends Controller
{
  function index(){
    $messages =  BatchMessage::with(['admin'=>fn($q)=>$q->select(['name', 'id'])])
    ->orderBy('created_at', 'DESC')
    ->get(['id', 'admin_id', 'body', 'created_at', 'title', 'status']);

    $statuses = [
      'pending'    => 'pendiente',
      'ready'      => 'listo',
      'processing' => 'enviando',
      'sent'       => 'enviado',
      'failed'     => 'fallido',
    ];

    $messages = $messages->map(function($m)use($statuses){
      $m->status = $statuses[$m->status];
      $m->fecha = $m->created_at->diffForHumans(null, true);
      return $m;
    });

    return response()->json(['data'=>$messages]);
  }

  function show(BatchMessage $batchMessage){
    return response()->json(['data'=>$batchMessage]);
  }

  function store(Request $request){
    $data = $request->validate([
      'admin_id' => 'required|exists:admins,id',
      'content' => 'required',
      'receivers' => 'required',
    ]);

    $data['title'] = 'prueba';
    $data['body'] = $data['content'];

    unset( $data['content'] );
    unset( $data['receivers'] );

    $batchMessage = BatchMessage::create($data);
    $batchMessage->receivers()->attach($request->receivers);

    return redirect()->route('admin.batch_messages.create')->with(['message'=>"Creado con exito"]);
  }

  function destroy(BatchMessage $batchMessage){
    $batchMessage->delete();
    return response()->json([], 200);
  }
}

