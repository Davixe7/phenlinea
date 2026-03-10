<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DeliveryResource;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = auth()->user()->deliveries()->with([
            'apartment' => function($q){$q->select(['id', 'name']);},
            'media'
        ])->get();
        return DeliveryResource::collection($deliveries);
    }
}
