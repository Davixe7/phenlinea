<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitPorteria;
use App\Visit;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $visits = Visit::whereAdminId(auth()->id())
                ->orderBy('created_at', 'DESC')
                ->limit(1000)
                ->with('visitor')
                ->get();

      return VisitPorteria::collection($visits);
    }
}
