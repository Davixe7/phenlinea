<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Petition as PetitionResource;
use App\Petition;
use App\Admin;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use GuzzleHttp\Client;

class PetitionController extends Controller
{
  public function __construct()
  {
    //$this->middleware('modules:requests');
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

    $media = null;

    if ($request->hasFile('media')) {
      foreach ($request->media as $file) {
        $media = $petition->addMedia($file)->toMediaCollection('attachments');
      }
    }

    $client = new Client([
      'base_uri' => 'http://asistbot.com/api/',
      'verify' => false
    ]);

    $data = [
      'id'         => $petition->id,
      'status'     => $petition->status,
      'phone'      => $petition->admin->name == 'admin' ? '584147912134' : '57' . $petition->phone,
      'adminName'  => $petition->admin->name,
      'adminPhone' => '57' . $petition->admin->phone,
      'media_url'  => $media ? $media->original_url : null
    ];

    $client->post('http://161.35.60.29/api/pqrs', ['query' => $data]);

    return redirect()->route('pqrs.create', ['admin' => $request->admin_id])->with(['message' => 'Petición creada con éxito']);
  }

  public function markAsRead(Petition $petition)
  {
    $petition->update(['read_at' => now(), 'status' => 'read']);

    $client = new Client([
      'base_uri' => 'http://asistbot.com/api/',
      'verify' => false
    ]);

    $data = [
      'id'        => $petition->id,
      'status'    => $petition->status,
      'phone'     => $petition->admin->name == 'admin' ? '584147912134' : '57' . $petition->phone,
      'adminName'  => $petition->admin->name,
      'adminPhone' => '57' . $petition->admin->phone,
      'media_url'  => null
    ];

    $client->post('http://161.35.60.29/api/pqrs', ['query' => $data]);

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
      $client = new Client([
        'base_uri' => 'http://asistbot.com/api/',
        'verify' => false
      ]);

      $data = [
        'id'        => $petition->id,
        'status'    => $petition->status,
        'phone'     => '57' . $petition->phone,

        'adminName'  => $petition->admin->name,
        'adminPhone' => $petition->admin->phone,
        'media_url'  => null
      ];

      $client->post('http://161.35.60.29/api/pqrs', ['query' => $data]);
    }
    $attachments = $petition->getMedia('attachments')->map(function ($media) {
      return $media->original_url;
    });
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

    $client = new Client([
      'base_uri' => 'http://asistbot.com/api/',
      'verify' => false
    ]);

    $data = [
      'id'        => $petition->id,
      'status'    => $petition->status,
      'phone'     => $petition->admin->name == 'admin' ? '584147912134' : '57' . $petition->phone,

      'adminName'  => $petition->admin->name,
      'adminPhone' => '57' . $petition->admin->phone,
      'media_url'  => null
    ];

    $client->post('http://161.35.60.29/api/pqrs', ['query' => $data]);

    if ($request->expectsJson()) {
      return response()->json(['data' => $petition]);
    }
    return redirect()->route('pqrs.show', ['petition' => $petition])->with('Peticion actualizada con éxito');
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
}
