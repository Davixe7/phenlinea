<?php

namespace App\Http\Controllers\API\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PetitionResource;
use App\Petition;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PetitionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $petitions = auth()->user()->petitions()->orderBy('created_at', 'DESC')->get();
    return PetitionResource::collection($petitions);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'admin_id'       => 'required|exists:admins,id',
      'extension_name' => 'required|string|max:4',
      'name'           => 'required',
      'phone'          => 'required',
      'phone_2'        => 'nullable',
      'description'    => 'required',
    ]);

    $petition = Petition::create($data);

    if ($request->hasFile('media')) {
      foreach ($request->media as $file) {
        $petition->addMedia($file)->toMediaCollection('attachments');
      }
    }

    /* Notificar Cambio de Estado del PQRS */

    return new PetitionResource($petition);
  }

  public function markAsRead(Petition $petition)
  {
    $petition->update(['read_at' => now(), 'status' => 'read']);
    /* Notificar Cambio de Estado del PQRS */
    return new PetitionResource( $petition );
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Petition $petition)
  {
    if( auth('api-admin')->check() && $petition->read_at == null){
      $petition->update(['read_at' => now(), 'status'=>'read']);
      /* Notificar Cambio de Estado del PQRS */
    }
    
    return new PetitionResource($petition);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Petition $petition)
  {
    $petition->update([
      'name'         => $request->name        ?: $petition->name,
      'description'  => $request->description ?: $petition->description,
      'phone'        => $request->phone       ?: $petition->phone,
      'status'       => $request->answer      ? 'replied' : ($request->read_at ? 'read' : $request->status),
      'answer'       => $request->answer,
      'replied_at'   => $request->answer ? now() : $petition->replied_at,
      'read_at'      => $request->read_at ? now() : $petition->read_at,
    ]);

    if ($files = $request->file('pictures')) {
      foreach ($files as $file) {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file->extension();
        $path     = storage_path('app/' . $file->storeAs('petitions/pictures/', $fileName));
        $petition->addMedia($path)->toMediaCollection('pictures');
      }
    }

    /* Notificar Cambio de Estado del PQRS */

    return new PetitionResource($petition);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Petition $petition)
  {
    $petition->delete();
    return response()->json([], 204);
  }

  public function qr(Request $request)
  {
    $admin_id = auth()->id();
    $url      = route('pqrs.create', ['admin' => $admin_id]);
    $filename = 'qr_' . now() . '.jpg';
    $path     = public_path("qr/pqrs_{$admin_id}_qr.jpeg");

    QrCode::size(410)->format('png')->generate($url, $path);
    $qr = \Image::make( $path );

    if( $request->type == 'template' ){
      $template_path = public_path("qr/pqrs_{$admin_id}_template_qr.jpeg");
      $template = \Image::make( storage_path('app/qrs/template.jpeg') );
      $template->insert($path, 'top-right', 76, 558)->save( $template_path );

      return response()->download($template_path, $filename, ['Content-Type' => 'image/jpg+xml']);
    }

    ob_end_clean();
    return response()->download($path, $filename, ['Content-Type' => 'image/jpg+xml']);
  }
}