<?php

namespace App\Traits;

use App\WhatsappClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;

class Whatsapp
{
  protected $api;
  protected $provider;

  public function __construct()
  {
    $this->provider  = WhatsappClient::whereEnabled(true)->first();
    $this->api = new Client(['base_uri' => $this->provider->base_url]);
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
      return $e->getMessage();
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

  public function send($number, $message, $media_url, $group_id)
  {
    $instance_id = $this->provider->delivery_instance_id;
    $query = compact('instance_id', 'number', 'message', 'media_url', 'group_id');
    Storage::append('devices.log', json_encode($query));

    try {
      $response = $this->api->get('send', compact('query'));
      $body     = json_decode($response->getBody());
      Storage::append('devices.log', json_encode($body));
      $status   = property_exists($body, 'status') ? $body->status : null;
      return $status;
    } catch (GuzzleException $e) {
      Storage::append('devices.log', 'ERROR: ' . $e->getMessage());
      return 'FAILED';
    }
  }
}
