<?php 
namespace App\Services;

use App\WhatsappClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class WaziperService {
    protected $api;

    public function __construct(WhatsappClient $client){
        $this->api = new Client([
            'base_uri' => $client->base_url,
            'query' => [
                'access_token' => $client->access_token
            ]
        ]);
    }

    public function createInstance(){
        try {
            $response = $this->api->get('create_instance');
            $data = json_decode($response->getBody(), true);
            if(isset($data['instance_id'])){
                return $data['instance_id'];
            }
        }
        catch (GuzzleException $e){
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function getQrcode($instance_id){
        try {
            $response = $this->api->get("get_qrcode?instance_id={$instance_id}");
            $data = json_decode($response->getBody(), true);
            if(isset($data['base64'])){
                return $data['base64'];
            }
        }
        catch (GuzzleException $e){
            Log::error($e->getMessage());
            throw $e;
        }
    }
}