<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Delivery;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Notifications\DeliveryWANotification;
use Illuminate\Support\Facades\Log;


class WhatsappController extends Controller
{ 
  public function sendDelivery(Request $request, $name = null)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();
    $media_url = null;

    $delivery = $extension->deliveries()->create();
    
    if ( $request->hasFile('media') ) {
      try {
        $image = $request->file('media');

        $resized = Image::make($image)->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $cropped = $resized->crop(500, 500, 
            intval(($resized->width() - 500) / 2), 
            intval(($resized->height() - 500) / 2)
        );

        $tempPath = storage_path('app/temp_cropped.jpg');
        $cropped->save($tempPath);
        $delivery->addMedia( $tempPath )->toMediaCollection('picture');
        $media_url = $delivery->getFirstMediaUrl('picture');
      } catch(Exception $e){
	Log::error($e->getMessage());
      }
    }

    if( count($extension->valid_whatsapp_phone_numbers) >= 1 ){
      $extension->notify( new DeliveryWANotification($extension, $media_url) );
      sleep(2);
    }

    // Mobile App Expects
    // $response = $data['media_url'] ? 'Message sent successfully' : ['message' => 'Message sent successfully'];
    // return response()->json(['data' => $response]);
    return response()->json(['data' => $media_url
                                       ? 'Message sent successfully'
                                       : ['message' => 'Message sent successfully']]);
  }
}

