<?php

namespace App\Http\Controllers;

use App\PushNotificationLog;
use App\DeviceToken;
use App\Http\Resources\PushNotificationLogResource;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function index(){
    return PushNotificationLogResource::collection( Auth::user()->push_notification_logs );
  }

  public function create(){
    $extensions = Auth::user()->extensions()->whereHas('device_tokens')->get();
    $logs       = Auth::user()->push_notification_logs()->orderBy('created_at', 'DESC')->get();
    return view('admin.messages.push_notifications', compact('extensions', 'logs'));
  }
  
  public function store(Request $request)
  {
    $request->validate([
        'extensions' => 'required|filled|array'
    ]);
    $serverToken   = 'AAAACJdIP-c:APA91bGkFBe6KFiTDZTN2oeXHHFnna-_GALk2U3ArDtU3sxlOl2M3XvwvG2wAwzrP3_qZpR0rIv5c6dpan-BTEvczstMs33BV63gnRKOvrbPVrvxOrkT9lW1TLBUIQpot9P-5YCdepSB';
    $extension_ids = $request->extensions;
    $device_tokens = DeviceToken::whereIn('extension_id', $extension_ids)
                    ->select(['extension_id', 'token_id'])
                    ->pluck('token_id')
                    ->toArray();

    $data = [
      'registration_ids' => $device_tokens,
      'data' => [
        'title' => Auth::user()->name,
        'body'  => $request->body,
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
      ],
      'notification' => [
        'title' => Auth::user()->name,
        'body'  => $request->body
      ]
    ];

    $json_data = json_encode($data);

    $client = new Client([
      'headers' => [
        'Authorization' => 'Bearer ' . $serverToken,
        'Content-Type'  => 'application/json',
    ]]);

    $fcm_request = $client->post('https://fcm.googleapis.com/fcm/send', ['body'  => $json_data]);
    $log = PushNotificationLog::create([
      'admin_id' => Auth::id(),
      'receivers_count' => count( $device_tokens ),
      'body' => $request->body,
      'sent_to_all' => $request->sent_to_all
    ]);
    $log->extensions()->attach( $extension_ids );
    
    return redirect()->route('push.create')->with(['message'=>'Notificación enviada con éxito']);
    //return $fcm_request->getBody();
  }

  public function destroy(PushNotificationLog $push_notification_log, Request $request){ 
    $push_notification_log->delete();
    
    if( $request->expectsJson() ){
      return response()->json(['data' => 'Registro eliminado con éxito']);
    }
    return redirect()->route('push.create')->with(['message'=>'Registro eliminado con éxito']);
  }
}
