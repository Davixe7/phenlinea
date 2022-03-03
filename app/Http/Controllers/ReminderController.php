<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Reminder as ReminderResource;
use App\Http\Requests\StoreReminder;
use App\Reminder;
use App\Traits\Uploads;

class ReminderController extends Controller
{
    use Uploads;
  
    public function __construct(){
      $this->middleware('modules:notifications');
      $this->authorizeResource(Reminder::class, 'reminder');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      switch (auth()->getDefaultDriver()) {
        case 'admin':
          return view('admin.reminders.index');
          break;
        case 'extension':
          $reminders = auth()->user()->reminders()->orderBy('created_at', 'DESC')->get();
          return view('resident.reminders', ['reminders'=>$reminders]);
          break;
      }
    }
    
    public function list(Request $request)
    {
      $reminders = auth()->user()->reminders()->extension( $request->extension )->orderBy('created_at', 'DESC')->get();
      return ReminderResource::collection( $reminders );
    }
    
    public function create()
    {
      return view('posts.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $reminder = Reminder::create([
        'title'        => $request->title,
        'description'  => $request->description,
        'admin_id'     => $request->user()->id,
        'extension_id' => $request->extension_id,
        'pictures'     => $this->upload($request, 'pictures'),
      ]);
      
      return new ReminderResource( $reminder );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Reminder $reminder)
    {
      return new ReminderResource( $reminder );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reminder $reminder)
    {
      $reminder->update([
        'title'       => $request->title ?: $reminder->title,
        'description' => $request->description ?: $reminder->description,
        'pictures'    => array_merge( $this->upload($request, 'pictures'), $reminder->pictures )
      ]);
      
      return new ReminderResource( $reminder );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reminder $reminder)
    {
      $reminder->delete();
      return response()->json(['data'=>'Reminder' . $reminder->id . ' deleted successfuly']);
    }
    
    public function deletePicture(Request $request, Reminder $reminder){
      $pictures = collect($reminder->pictures)->filter(function($p) use($request){
        return $p['path'] != $request->picture;
      });
      $reminder->pictures = $pictures;
      $reminder->save();
      return new ReminderResource( $reminder );
    }
}
