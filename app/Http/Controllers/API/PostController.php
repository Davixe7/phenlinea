<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResource;
use App\Http\Requests\StorePost;
use App\Post;
use App\Traits\Uploads;

class PostController extends Controller
{
    use Uploads;
    
    public function __construct(){
      $this->authorizeResource(Post::class, 'post');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      return PostResource::collection( auth()->user()->posts()->type( $request->type ?: 'post' )->get() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
      $post = Post::create([
        'title'       => $request->title,
        'description' => $request->description,
        'pictures'    => $this->upload($request, 'pictures'),
        'attachments' => $this->upload($request, 'attachments'),
        'admin_id'    => auth()->user()->id
      ]);
      
      return new PostResource( $post );
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
      $post->update([
        'title' => $request->title,
        'description' => $request->description,
        'pictures'    => array_merge( $this->upload($request, 'pictures'), $post->pictures ?: [] ),
        'attachments' => array_merge( $this->upload($request, 'attachments'), $post->pictures ?: [] )
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
      return response()->json(['data'=>'Post ' . $post->id . ' deleted successfuly']);
    }
    
    public function deletePicture(Request $request, Post $post){
      $pictures = collect($post->pictures)->filter(function($p) use($request){
        return $p['path'] != $request->picture;
      });
      $post->pictures = $pictures;
      $post->save();
      return new PostResource( $post );
    }
}
