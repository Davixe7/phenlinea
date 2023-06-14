<?php

namespace App\Http\Controllers\API;

use App\Visit;
use Illuminate\Http\Request;
use App\Http\Resources\VisitPorteria;
use App\Http\Controllers\Controller;
use App\Traits\Uploads;
use Illuminate\Support\Facades\Storage;

class VisitController extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $visits = auth()->user()->admin->visits()->orderBy('created_at', 'DESC')->get();
      return VisitPorteria::collection( $visits );
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
        'name'      => 'required'
      ]);
      
      $extension_id = $request->extension_id;
      
      if( $request->apartment ){
          $extension = auth()->user()->extensions()->whereName( $request->apartment )->firstOrFail();
          $extension_id = $extension->id;
      }

      $visit = Visit::create([
        "name"         => $request->name,
        "dni"          => $request->dni,
        "phone"        => $request->phone,
        "plate"        => $request->plate,
        "checkin"      => now(),
        "type"         => $request->type,
        "company"      => $request->company,
        "arl"          => $request->arl,
        "eps"          => $request->eps,
        "extension_id" => $extension_id,
        "admin_id"     => auth()->user()->admin_id
      ]);
      
      Storage::append('visits.log', json_encode($request->all()));

      if( $file = $request->file('picture') ){
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file->extension();
        $path     = $file->storeAs('/visits/picture/', $fileName);
        $path     = storage_path( "app/{$path}" );
        $visit->addMedia( $path )->toMediaCollection('picture');
      }

      return new VisitPorteria( $visit );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visit $visit)
    {
      $visit->update(["checkout" => now()]);
      return new VisitPorteria( $visit );
    }
}
