<?php

namespace App\Http\Controllers\API;

use App\Events\VisitCreatedEvent;
use App\Visit;
use App\Visitor;
use Illuminate\Http\Request;
use App\Http\Resources\VisitPorteria;
use App\Http\Controllers\Controller;
use App\Traits\Devices;
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
    public function index(Request $request)
    {
      $filter    = $request->filter ?: '';
      $date_from = $request->date_from;
      $date_to   = $request->date_to;
      $per_page  = $request->per_page ?: 200;

      $visits = auth()->user()->admin->visits()->orderBy('created_at', 'DESC');

      if( $filter ){
        Storage::append('visitsfilter.log', $filter);
        $visits
        ->where('extension_name', 'like', "%" . $filter . "%")
        ->orWhere('plate', 'like', "%" . $filter . "%");
      }

      if( $date_from && $date_to ){
        $visits->whereBetween('created_at', [$date_from, $date_to]);
      }
      $visits = $visits->paginate($per_page);

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
        'name' => 'required',
        'extension_name' => 'required',
        'dni' => 'required'
      ]);

      $visitor = Visitor::updateOrcreate(
      ["id" => $request->visitor_id],
      [
        "type"         => $request->type,
        "company"      => $request->company,
        "arl"          => $request->arl,
        "eps"          => $request->eps,
        "dni"          => $request->dni,
        "name"         => $request->name,
        "phone"        => $request->phone,
      ]);

      $visit = Visit::create([
        "admin_id"     => auth()->user()->admin_id,
        "extension_name" => $request->extension_name,
        "visitor_id"    => $visitor ? $visitor->id : $request->visitor_id,
        "checkin"       => now(),
        "start_date"    => now(),
        "end_date"      => now()->addHours( auth()->user()->admin->visits_lifespan ?: 24 ),
        "plate"         => $request->plate,
        "authorized_by" => $request->authorized_by,
        "note" 		      => $request->note
      ]);

      if( $picture = $request->file('picture') ){
        $visitor->addMedia( $picture )->toMediaCollection('picture');
      }

      $visit->addPwd();

      VisitCreatedEvent::dispatch( $visit );
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
