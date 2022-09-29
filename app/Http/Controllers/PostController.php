<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResource;
use App\Post;
use App\Traits\Uploads;

class PostController extends Controller
{
    use Uploads;
    
    public function __construct(){
      $this->middleware('modules:posts');
      $this->authorizeResource(Post::class, 'post');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $posts = ['posts' => auth()->user()->posts()->where('type', 'post')->orderBy('created_at', 'DESC')->get()];
      $role = auth()->getDefaultDriver();
      if( $role == 'admin' ){
        return view('admin.posts', $posts);
      }
      return view('posts._index', $posts);
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
        'type'        => 'post',
        'title'       => $request->title,
        'description' => $request->description,
        'admin_id'    => $request->user()->id,
        'pictures'    => $this->upload($request, 'pictures'),
        'attachments' => $this->upload($request, 'attachments')
      ]);
      
      return redirect()->route('posts.index')->with(['message'=>'Publicación creada con éxito']);
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
      $uploadedPictures    = $this->upload($request, 'pictures');
      $uploadedAttachments = $this->upload($request, 'attachments');
      
      $post->update([
        'title'       => $request->title ?: $post->title,
        'description' => $request->description ?: $post->description,
        'pictures'    => array_merge($uploadedPictures, $post->pictures),
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
    public function destroy(Post $post)
    {
      $post->delete();
      return redirect()->route('posts.index')->with(['message'=>'Publicación eliminada con éxito']);
    }
    
    public function deletePicture(Request $request, Post $post){
      $pictures = collect($post->pictures)->filter(function($p) use($request){
        return $p['path'] != $request->picture;
      });
      $post->pictures = $pictures;
      $post->save();
      return new PostResource( $post );
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
