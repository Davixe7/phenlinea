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
      $visits = auth()->user()->visits()->orderBy('created_at', 'DESC')->limit(1000)->with('visitor')->get();
      return view('admin.visits', ['visits' => $visits]);
    }
}
