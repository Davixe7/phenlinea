<?php

namespace App\Http\Controllers;

use App\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = MessageTemplate::with('fields')->get();
        return response()->json(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MessageTemplate  $messageTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(MessageTemplate $messageTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MessageTemplate  $messageTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MessageTemplate  $messageTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageTemplate $messageTemplate)
    {
        //
    }
}
