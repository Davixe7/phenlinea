<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extension;
use App\Jobs\ProcessDeliveries;


class WhatsappController {
    
    public function sendDelivery(Extension $extension, Request $request){
        if( !$extension->admin_id == auth()->user()->admin_id ){
            abort('404', 'No tiene una extensión asociada con el número especificado');
        }
        $media = null;
        $phones[] = '57' . $extension->phone_1;
        if( $extension->phone_2 ){
            $phones[] = '57' . $extension->phone_2;
        }
        
        if( $request->hasFile('media') ){
            $file = $request->file('media');
            $media = $extension->addMedia( $file )->toMediaCollection('deliveries');
        }
        
        foreach( $phones as $phone ){
            ProcessDeliveries::dispatch($extension, $phone, $media);
        }
        
        return response()->json(['data'=>'Message sent successfully']);
    }
        
}

    
