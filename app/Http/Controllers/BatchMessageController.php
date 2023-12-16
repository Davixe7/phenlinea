<?php

namespace App\Http\Controllers;

use App\BatchMessage;
use Illuminate\Http\Request;

class BatchMessageController extends Controller
{
    function index(){
      return BatchMessage::all();
    }
}
