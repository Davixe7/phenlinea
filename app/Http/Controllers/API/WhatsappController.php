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
        
        if( $request->hasFile('media') ){
            $file = $request->file('media');
            $media = $extension->addMedia( $file )->toMediaCollection('deliveries');
        }
        
        ProcessDeliveries::dispatch($extension, '57' . $extension->phone_1, $media);
        if( $extension->phone_2 ){
            ProcessDeliveries::dispatch($extension, '57' . $extension->phone_2, $media);
        }
        
        return response()->json(['data'=>'Message sent successfully']);
    }
        
}

    
