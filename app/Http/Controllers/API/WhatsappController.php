<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extension;
use App\Jobs\ProcessDeliveries;
use GuzzleHttp\Client;


class WhatsappController {
    
    public function sendDelivery(Extension $extension, Request $request){
        if( !$request->name ){
            abort('422', 'El campo extension es obligatorio');
        }
        
        $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();
        
        if( !$extension || (!$extension->admin_id == auth()->user()->admin_id) ){
            abort('404', 'No tiene una extensión asociada con el número especificado');
        }
        
        $media = null;
        
        if( $request->hasFile('media') ){
            $file = $request->file('media');
            $media = $extension->addMedia( $file )->toMediaCollection('deliveries');
        }
        
        $client = new Client([
          'base_uri' => 'http://asistbot.com/api/',
          'verify' => false
        ]);
        
        $data = [
            'extension' => $extension->name,
            'admin'     => $extension->admin->name,
            'phone'     => $request->name == '1000' ? '584147912134' : '57' . $extension->phone_1,
            'media_url' => $media ? $media->original_url : ''
        ];
        
        //ProcessDeliveries::dispatch($extension, '57' . $extension->phone_1, $media);
        $response = $client->post('http://161.35.60.29/api/hello', ['query' => $data]);
        
        if( $extension->phone_2 ){
            $data['phone'] = '57' . $extension->phone_2;
            $response = $client->post('http://161.35.60.29/api/hello', ['query' => $data]);
        }
        
        return response()->json(['data'=>'Message sent successfully']);
    }
        
}

    
