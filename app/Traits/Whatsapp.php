<?php

namespace App\Traits;

use App\WhatsappClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Whatsapp
{ 
  protected $api;

  public function __construct(){
    $provider  = WhatsappClient::whereEnabled(true)->first();
    $this->api = new Client(['base_uri' => $provider->base_url]);
  }

  public function getInstanceId(){
    try {
      $response    = $this->api->get('create_instance');
      $body        = json_decode($response->getBody());
      $instance_id = property_exists( $body, 'instance_id' ) ? $body->instance_id : null;
    }
    catch(GuzzleException $e){
      return $e->getMessage();
    }

    return $instance_id;
  }

  public function getQrCode($instance_id){
    try {
      $response    = $this->api->get('get_qrcode', ['query'=>compact('instance_id')]);
      $body        = json_decode($response->getBody());
      $base64      = property_exists( $body, 'base64' ) ? $body->base64 : null;
    }
    catch(GuzzleException $e){
      return $e->getMessage();
    }

    return $base64;
  }
}