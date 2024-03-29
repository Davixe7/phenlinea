<?php

namespace App\Traits;

use App\WhatsappClient;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;

class Whatsapp
{
  protected $api;
  public $provider;

  public function __construct(?WhatsappClient $provider = null)
  {
    try {
      $provider = $provider ?: WhatsappClient::whereEnabled(true)->firstOrFail();
      
      $this->provider = $provider;
      $this->api      = new Client(['base_uri' => $provider->base_url]);
    }
    catch(Exception $e){
      Storage::append('messages.log', now() . $e->getMessage());
    }
  }

  public function validateInstance($instance_id, $phone){
    $query    = compact('instance_id', 'phone');
    try {
      $response = $this->api->get('validate', compact('query'));
      $body     = json_decode($response->getBody());
      $data     = property_exists($body, 'data') ? $body->data : null;
      return $data;
    }
    catch( Exception $e ){
      Storage::append('whatsapp.errors.log', $e->getMessage());
    }
  }

  public function getInstanceId()
  {
    try {
      $response    = $this->api->get('create_instance');
      $body        = json_decode($response->getBody());
      $instance_id = property_exists($body, 'instance_id') ? $body->instance_id : null;
    } catch (GuzzleException $e) {
      return $e->getMessage();
    }

    return $instance_id;
  }

  public function getQrCode($instance_id)
  {
    try {
      $response    = $this->api->get('get_qrcode', ['query' => compact('instance_id')]);
      $body        = json_decode($response->getBody());
      $base64      = property_exists($body, 'base64') ? $body->base64 : null;
    } catch (GuzzleException $e) {
      Storage::append('messages.log', $e->getMessage());
      return null;
    }

    return $base64;
  }

  public function setWebhook($instance_id, $webhook_url)
  {
    try {
      $response    = $this->api->get('set_webhook', ['query' => compact('instance_id', 'webhook_url')]);
      $body        = json_decode($response->getBody());
      $status      = property_exists($body, 'status') ? $body->status : null;
    } catch (GuzzleException $e) {
      return $e->getMessage();
    }

    return $status;
  }

  public function logout($instance_id)
  {
    try {
      $response    = $this->api->get('logout', ['query' => compact('instance_id')]);
      $body        = json_decode($response->getBody());
      $status      = property_exists($body, 'status') ? $body->status : null;
    } catch (GuzzleException $e) {
      return $e->getMessage();
    }

    return $status;
  }

  public function send($options)
  {
    if( $options['number'] == '574147912134' ){
      $options['number'] = '584147912134';
    }

    if(!key_exists('instance_id', $options)){
      $options['instance_id'] = $this->provider->delivery_instance_id;
    }

    if(!key_exists('access_token', $options)){
      $options['access_token'] = $this->provider->access_token;
    }

    if( !$options['number'] || $options['number'] == '57' || $options['number'] == 'null'){
      Storage::append('messages.log', now() . ' Invalid phone number: ' . $options['number']);
      return;
    }

    try {
      $response = $this->api->get('send', ['query'=>$options]);
      $body     = $response->getBody();
      Storage::append('messages.log', now() . $body);
    } catch (GuzzleException $e) {
      Storage::append('messages.log', now() . 'ERROR: ' . $e->getMessage());
      return false;
    }
  }
}
