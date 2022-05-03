<?php

namespace App\Http\Controllers\API;

use App\Notification;
use App\Events\DeliveryOrderReceived;
use App\Events\GlobalNotificationSent;
use App\Events\BulkSmsSent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SmsResource;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SmsController extends Controller
{
  public function notifyDelivery(Request $request){
    $extension = Auth::user()->extensions()->name( $request->name )->firstOrFail();
    //if( $request->expectsJson() ){
    //    $extension = Auth::user()->extensions()->findOrFail( $request->name );
    //}else{
    //    $extension = Auth::user()->extensions()->name( $request->name )->firstOrFail();
    //}
    $firstPhone1  = ( $extension->phone_1 ) ? $extension->phone_1[0] : null;
    $firstPhone2  = ( $extension->phone_2 ) ? $extension->phone_2[0] : null;
      
    if( $firstPhone1 != '3' && $firstPhone2 != '3' ){
        return response()->json(['data'=>'La extensión no tiene números de teléfono validos para notificación'],400);
        //abort(400, 'La extensión no tiene números de teléfono validos para notificación');
    }
    
    event( new DeliveryOrderReceived( $extension ) );
    $this->storeNotification($request, $extension->id, 'delivery');
    return response()->json(['data'=>['message' => 'Notificacion enviada'] ]);
  }
  
  public function notifyGlobal(Request $request){
    $request->validate([
      'type' => [
        function($att, $value, $fail){
          if( !in_array($value, ['services', 'admin']) ){
            $fail('Tipo de notificacion invalido. Valores validos: services, admin');
          }
        }
      ]
    ]);
    $extensions  = Auth::user()->extensions;
    
    if( $extensions->count() ){
      $pastDays = Auth::user()->daysSinceLastNotification( $request->type );
      if( !is_null($pastDays) && ($pastDays < 30) ){
        return response()->json(['data'=>['message' =>'Ya envió una notificación global hace menos de 30 días'] ], 403);
      }else {
        event( new GlobalNotificationSent( $request->type ) );
        $this->storeNotification($request, null, $request->type);
        return response()->json(['data'=>['message' => 'Notificacion global enviada'] ]);
      }
    }
  }
  
  public function bulkMessage(Request $request){
    $request->validate([
      'message'  => 'string|min:20|max:160',
      'receiver' => 'required'
    ]);
    
    $user = Auth::user();
    // if( !$user->sms_enabled ){
    //   return response()->json(['data'=>['message'=>'Envio masivo de SMS no habilitado'] ], 403);
    // }
    
    $extensions = $user->extensions()
                       ->whereIn('id', $request->receiver)
                       ->get(['phone_1', 'phone_2', 'phone_3', 'phone_4', 'owner_phone']);
    $receiver = $this->concatExtensionsNumbers($extensions, $request->receiverType);
    $total = count($receiver);
    
    event( new BulkSmsSent( $receiver, $request->message ));
    $sms_log_entry = $this->storeNotification($request, null, 'bulk', $request->message, $total);
    return response()->json(['data'=>$sms_log_entry]);
  }
  
  public function storeNotification(Request $request, $id, $type, $content = null, $count = null){
    return Notification::create([
      'date'         => Carbon::now(new \DateTimeZone('America/Bogota')),
      'admin_id'     => !auth()->user()->admin_id ? auth()->user()->id : null,
      'porteria_id'  => auth()->user()->admin_id  ? auth()->user()->id : null,
      'extension_id' => $id,
      'type'         => $type,
      'content'      => $content,
      'count'        => $count,
    ]);
  }
  
  public function history(Request $request){
    $log = Auth::user() ? SmsResource::collection( Auth::user()->logs->load('extension:id,name') ) : null;
    if( $request->expectsJson() ){
      return response()->json(['data'=>$log]);
    }
    return view('admin.sms.log', ['log'=>$log]);
  }
  
  public function concatExtensionsNumbers($extensions, $receiverType){
    $numbers = [];
    $number = '';
    if( $receiverType == 'extensions' ){
        $extensions->each(function($extension) use(&$numbers, &$number) {
            foreach(range(1,4) as $i){
                if( ($number = $extension["phone_$i"]) && substr($number,0,1) == '3' ){
                    $numbers[] = '57' . $number;
                }
            }
        });
    }
    else{
        $extensions->each(function($extension) use(&$numbers, &$number){
            if( ($number = $extension->owner_phone) && substr($number,0,1) == '3'){
                $numbers[] = '57' . $number;
            }
        });
    }
    return $numbers;
  }
  
  public function dep_concatExtensionsNumbers($extensions, $receiverType){
    $numero = '';
    if( $receiverType == 'extensions' ){
      foreach( $extensions as $e ){
        if( $e->phone_1 ) { $numero .= $e->phone_1 . ','; }
        if( $e->phone_2 ) { $numero .= $e->phone_2 . ','; }
        if( $e->phone_3 ) { $numero .= $e->phone_3 . ','; }
        if( $e->phone_4 ) { $numero .= $e->phone_4 . ','; }
      }
    }else {
      foreach( $extensions as $e){
        $numero .= $e->owner_phone . ',';
      }
    }
    return $numero;
  }
  
}

