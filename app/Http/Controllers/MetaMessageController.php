<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BatchMessage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MetaMessageController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $batch = BatchMessage::create([
            'template_name' => $request->template_name,
            'admin_id'      => auth()->id(),
            'title'         => $request->title,
            'body'          => 'test',
            'media_url'     => $this->saveMediaUrl($request),
            'status'        => 'ready'
        ]);

        $batch->receivers()->attach($request->receivers);
        $batch->fields()->attach($request->fields);
        return response()->json([], 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function saveMediaUrl($request){
        if (!$file = $request->file('file')) return null;

        $clearFileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = $clearFileName . time() . "." . $file->extension();
        $path = $file->storeAs('/public/whatsapp_attachments', $fileName);
        $media_url = asset("/storage/whatsapp_attachments/{$fileName}");
        Storage::append('batch_messages_media.log', $path);

        return $media_url;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
