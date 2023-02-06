<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Petition as PetitionResource;
use App\Petition;
use App\Admin;
use Illuminate\Support\Str;
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

    $path = public_path("qr/pqrs_" . Str::slug(auth()->user()->name) . "_qr.svg");
    \QrCode::size(500)->format('svg')->generate(url("unidades/" . Str::slug(auth()->user()->name) . "/pqrs"), $path);
    $qr = ['path' => $path, 'url' => url("qr/pqrs_" . Str::slug(auth()->user()->name) . "_qr.svg")];

    $pqrss = auth()->user()->petitions()->orderBy('created_at', 'DESC')->get();
    return view('admin.petitions', compact('pqrss', 'qr'));
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
      'admin_id'     => $request->admin_id,
      'name'         => $request->name,
      'apto'         => $request->apto,
      'phone'        => $request->phone,
      'phone_2'      => $request->phone_2,
      'description'  => $request->description,
      'status'       => 'pending'
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
      'id'        => $petition->id,
      'status'    => $petition->status,
      'phone'     => $petition->admin->name == 'Jardines Del Eden' ? '584147912134' : '57' . $petition->phone,

      'adminName'  => $petition->admin->name,
      'adminPhone' => $petition->admin->phone,
      'media_url' => $media ? $media->original_url : null
    ];

    $client->post('http://161.35.60.29/api/pqrs', ['query' => $data]);

    return redirect()->route('pqrs.create', ['admin' => Str::slug($petition->admin->name)])->with(['message' => 'Petición creada con éxito']);
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
      'phone'     => '57' . $petition->phone,

      'adminName'  => $petition->admin->name,
      'adminPhone' => $petition->admin->phone,
      'media_url'  => null
    ];

    $client->post('http://161.35.60.29/api/pqrs', ['query' => $data]);

    return response()->json(['data' => $petition]);
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
      'status'       => $request->answer      ? 'answered' : $petition->status,
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
      'phone'     => '57' . $petition->phone,

      'adminName'  => $petition->admin->name,
      'adminPhone' => $petition->admin->phone,
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

  public function qr()
  {
    $adminId = auth()->id();
    $path = public_path("qr/pqrs_" . $adminId . "_qr.svg");

    if (!is_file($path)) {
      \QrCode::size(500)->format('svg')->generate(url("unidades/" . auth()->user()->slug . "/pqrs"), $path);
    }
    $qr = [
      'path' => $path,
      'url'  => url("qr/pqrs_" . $adminId . "_qr.svg")
    ];
    ob_end_clean();
    return response()->download($qr['path'], 'qr_' . now() .  '.svg', ['Content-Type' => 'image/svg+xml']);
  }
}
