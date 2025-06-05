<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MetaNotificationService {

protected $api;
protected $fromNumberId;

public function __construct(){
    $this->fromNumberId = env('META_FROM_NUMBER_ID');
    $this->api = new Client([
        'base_uri' => env('META_BASE_URL'),
        'headers'  => [
            'language' => 'en_ES',
            'timeZone' => 'America/Bogota',
            'content-type' => 'application/json',
            'authorization' => "Bearer " . env('META_ACCESS_TOKEN')
        ]
    ]);
}

public function send($payload){
    $data = [
        "messaging_product" => "whatsapp",
        "to" => $payload['to_number'],
        "type" => "template",
        "template" => [
            "name" => $payload['template_name'],
            "language" => [
                "code" => "es_CO"
            ],
            "components" => $payload['components']
        ]
    ];
    
    try {
        if( array_key_exists('from_number_id', $payload) ){
            $this->fromNumberId = $payload['from_number_id'];
            Log::info('FROM NUMBER ID OVERRIDEN: ' . $this->fromNumberId);
        }
        
        $response = $this->api->post("$this->fromNumberId/messages", ['json' => $data]);
        $status   = $response->getStatusCode();
        $contents = $response->getBody()->getContents();
        $res = ['status'=>$status, 'contents'=>$contents];
        Log::info(json_encode($res));
        return $res;
    }
    catch (\Exception $e) {
        Log::error($e->getMessage());
    }
}

}