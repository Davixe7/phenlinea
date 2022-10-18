<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Admin;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $client;

    public function __construct(){
      $this->client = new Client(['base_uri'=>'https://asistbot.com/api/']);
    }

    public function createInstance(){
      $response = $this->client->post('createinstance.php', ['query'=>[
        'access_token' => '3f8b18194536bdafa301c662dc9caa4c'
      ]]);

      return $response;
    }

    public function getQrCode(){
      $response = $this->client->post('getqrcode.php', ['query'=>[
        'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
        'instance_id'  => auth()->user()->whatsapp_instance_id
      ]]);

      return $response;
    }

    public function index()
    {
      $instance_id = auth()->user()->whatsapp_instance_id;
      
      if( $instance_id == null ){
        $response = $this->createInstance();
        $instance_id = json_decode($response->getBody(), true)['instance_id'];

        $keyUsed = Admin::where('whatsapp_instance_id', $instance_id)->exists();
        
        if( $keyUsed ){
          Admin::where('whatsapp_instance_id', $instance_id)->update(['whatsapp_instance_id'=>null]);
        }
        auth()->user()->update(['whatsapp_instance_id' => $instance_id]);
      }
      
      if( auth()->user()->whatsapp_status == 'online' ){
          return 'Puedes ver el panel';
      }
      
      if( auth()->user()->whatsapp_status == 'offline' ){
        $response = $this->client->post('setwebhook.php', ['query'=>[
            'enable'       => 'true',
            'instance_id'  => $instance_id,
            'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
            'webhook_url'  => route('whatsapp.hook')
        ]]);
      }

      $response = $this->getQrCode();
      $data = json_decode($response->getBody(), true);
      if( array_key_exists('base64', $data) ){
        return view('whatsapp', ['base64'=>$data['base64']]);
      }
      return redirect()->route('whatsapp.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logHook( Request $request )
    {
        if( $request->event == 'state' ){
            Admin::where('whatsapp_instance_id', $request->instance_id)->update(['whatsapp_status'=>'offline', 'whatsapp_instance_id'=>null]);
        }else {
            Admin::where('whatsapp_instance_id', $request->instance_id)->update(['whatsapp_status'=>'online']);   
        }
        $data = json_encode($request->all());
        Storage::append('file.log', $data);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
