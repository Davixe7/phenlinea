<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Petition as PetitionResource;
use App\Petition;
use App\Admin;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class PetitionController extends Controller
{
  protected $client;

  public function __construct()
  {
    //$this->middleware('modules:requests');
    $this->client = new Client([
      'base_uri' => 'http://asistbot.com/api/',
      'verify' => false
    ]);
  }

  public function getMessage($petition){
    if( ($petition->read_at == null) && ($petition->replied_at == null) ){
      $status = 'Pendiente';
    }
    else if( $petition->read_at != null && ($petition->replied_at == null)){
      $status = 'Leído';
    }
    else {
      $status = "Respuesta enviada";
    }

    $petitionId = str_pad($petition->id, 4, '0', STR_PAD_LEFT);
    $statuses   = ['pending'=>'pendiente', 'read'=>'Leído', 'replied'=>'Respuesta enviada'];
    $status     = $statuses[ $petition->status ];
    $link       = route('pqrs.show', compact('petition'));

    $message = "Unidad: *{$petition->admin->name}* \n\n";
    $message .= "Su PQRS ha sido actualizado con éxito. Y su código de seguimiento es el *{$petitionId}* \n\n";
    $message .= "Estado: *{$status}* \n\n";
    $message .= "Link de seguimiento del estado: {$link} \n\n";
    $message .= "Servicio prestado por PHenlinea.com";
    return $message;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if ($request->expectsJson()) {
      return PetitionResource::collection(auth()->user()->petitions()->orderBy('created_at', 'DESC')->get());
    }

    $pqrss = auth()->user()->petitions()->orderBy('created_at', 'DESC')->get();

    return view('admin.petitions.index', compact('pqrss'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Admin $admin, Request $request)
  {
    return view('public.pqrs.create', ['admin' => $admin]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'description' => 'required'
    ]);

    $petition = Petition::create([
      'admin_id'       => $request->admin_id,
      'name'           => $request->name,
      'extension_name' => $request->extension_name,
      'phone'          => $request->phone,
      'phone_2'        => $request->phone_2,
      'description'    => $request->description,
      'status'         => 'pending'
    ]);

    if ($request->hasFile('media')) {
      foreach ($request->media as $file) {
        $petition->addMedia($file)->toMediaCollection('attachments');
      }
    }

    $this->notifyPetitionUpdate($petition);

    return redirect()->route('pqrs.create', ['admin' => $request->admin_id])->with(['message' => 'Petición creada con éxito']);
  }

  public function markAsRead(Petition $petition)
  {
    $petition->update(['read_at' => now(), 'status' => 'read']);
    $this->notifyPetitionUpdate($petition);
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
    if ((auth()->user()) && (auth()->user()->nit != null) && ($petition->read_at == null)) {
      $petition->update(['read_at' => now()]);
      $this->notifyPetitionUpdate($petition);
    }

    $attachments = $petition->getMedia('attachments')->pluck('original_url')->toArray();
    
    return view('public.pqrs.show', ['pqrs' => $petition, 'attachments' => $attachments]);
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
      'status'       => $request->answer      ? 'replied' : $petition->status,
      'answer'       => $request->answer,
      'replied_at'   => $request->answer ? now() : $petition->replied_at
    ]);

    if ($files = $request->file('pictures')) {
      foreach ($files as $file) {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file->extension();
        $path     = storage_path('app/' . $file->storeAs('petitions/pictures/', $fileName));
        $petition->addMedia($path)->toMediaCollection('pictures');
      }
    }

    $this->notifyPetitionUpdate($petition);

    return $request->expectsJson()
    ? response()->json(['data' => $petition])
    : redirect()->route('pqrs.show', ['petition' => $petition])->with('Peticion actualizada con éxito');
  }

  public function deletePicture(Request $request, Petition $petition)
  {
    $pictures = collect($petition->pictures)->filter(function ($p) use ($request) {
      return $p['path'] != $request->picture;
    });
    $petition->pictures = $pictures;
    $petition->save();
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
    return response()->json(['data' => "Petition $petition->id deleted successfuly"]);
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

  public function notifyPetitionUpdate($petition){
    $data = [
      'access_token' => env('ASISTBOT_ACCESS_TOKEN'),
      'instance_id'  => env('ASISTBOT_INSTANCE_ID'),
      'number'       => $petition->phone,
      'message'      => $this->getMessage($petition),
      'type'         => 'text'
    ];

    $response = $this->client->post('send.php', ['query'=>$data]);
    Storage::append('pqrs.log', $response->getBody());
  }
}
