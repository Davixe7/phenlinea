<?php

namespace App\Http\Controllers;

use App\Http\Resources\VisitPorteria;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $visits = auth()->user()->visits()->orderBy('created_at', 'DESC')->get();
      return view('admin.visits', ['visits' => VisitPorteria::collection( $visits )]);
    }
}
