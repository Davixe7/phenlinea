<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DocumentosController extends Controller
{
    public function index(){
      return view('super.documentos.index');
    }

    public function store(Request $request){
      $request->validate([
        'file' => 'required|file|max:5000',
        'filename' => 'required|string'
      ]);

      if( !Storage::exists( $folder = 'public/documentos' ) ){
        Storage::makeDirectory($folder);
      }

      $ext  = $request->file('file')->getClientOriginalExtension();

      $path = $request->file->storeAs($folder,$request->filename.".".$ext);
      $path = storage_path("app/$path");

      return redirect()->route('admin.documentos.index')->with(['message'=>'Archivo guardado exitosamente']);
    }
}
