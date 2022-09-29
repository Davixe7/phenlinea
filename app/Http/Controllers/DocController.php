<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResource;
use App\Post;
use App\Traits\Uploads;

class DocController extends Controller
{
    use Uploads;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
      $role = auth()->getDefaultDriver();
      $docs = auth()->user()->posts()->whereType('doc')->get();
      
      if( $role == 'admin' ){
        return view('admin.docs', ['docs' => $docs]);
      }
      return view('resident.docs', ['docs' => $docs]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $post = Post::create([
        'title'       => $request->title,
        'description' => $request->description,
        'admin_id'    => $request->user()->id,
        'attachments' => $this->upload($request, 'attachments'),
        'type' => 'doc'
      ]);

      if( $request->expectsJson() ){
        return new PostResource( $post );
      }

      return redirect()->route('docs.index')->with(['message'=>'Manual guardado con éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
      return new PostResource( $post );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
      $uploadedAttachments = $this->upload($request, 'attachments');
      
      $post->update([
        'title'       => $request->title ?: $post->title,
        'description' => $request->description ?: $post->description,
        'attachments' => array_merge($uploadedAttachments, $post->attachments)
      ]);
      
      return new PostResource( $post );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
      $post->delete();
      if( $request->expectsJson() ){
        return response()->json(['data'=>'Post' . $post->id . ' deleted successfuly']);
      }
      return redirect()->route('docs.index')->with(['message'=>'Manual eliminado con éxito']);
    }
    
    public function deleteAttachment(Request $request, Post $post){
      $attachments = collect($post->attachments)->filter(function($file) use($request){
        return $file['path'] != $request->attachment;
      });
      $post->attachments = $attachments;
      $post->save();
      return new PostResource( $post );
    }
}
