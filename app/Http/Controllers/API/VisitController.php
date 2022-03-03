<?php

namespace App\Http\Controllers\API;

use App\Visit;
use App\Attachment;
use Illuminate\Http\Request;
use App\Http\Resources\VisitPorteria;
use App\Http\Controllers\Controller;
use App\Traits\Uploads;

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
      $visit = Visit::create([
        "name"         => $request->name,
        "dni"          => $request->dni,
        "phone"        => $request->phone,
        "plate"        => $request->plate,
        "checkin"      => \Carbon\Carbon::now(),
        "type"         => $request->type,
        "company"      => $request->company,
        "arl"          => $request->arl,
        "eps"          => $request->eps,
        "extension_id" => $request->apartment,
        "admin_id"     => auth()->user()->admin_id
      ]);

      $attachments = $this->upload($request, 'picture');
      foreach($attachments as $picture){
        Attachment::create([
          'url' => $picture['url'],
          'path' => $picture['path'],
          'visit_id' => $visit->id
        ]);
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
      $visit->update([
        "checkout" => \Carbon\Carbon::now()
      ]);
      return new VisitPorteria( $visit );
    }
}
