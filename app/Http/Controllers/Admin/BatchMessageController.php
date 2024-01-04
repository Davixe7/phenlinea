<?php

namespace App\Http\Controllers\Admin;

use App\BatchMessage;
use App\Http\Controllers\Controller;

class BatchMessageController extends Controller
{
  function index(){
    $messages =  BatchMessage::with(['admin'=>function($query){
      $query->select(['name', 'id']);
    }])
    ->orderBy('created_at', 'DESC')
    ->get(['created_at', 'status', 'admin_id']);

    return view('super.batch_messages', compact('messages'));
  }
}

