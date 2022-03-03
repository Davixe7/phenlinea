<?php

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\ProductAttachment  $productAttachment
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAttachment $productAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductAttachment  $productAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductAttachment $productAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductAttachment  $productAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductAttachment $productAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductAttachment  $productAttachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
      $attachment->delete();
      return response()->json(['data'=>"Attachment $attachment->id deleted successfully"]);
    }
}
