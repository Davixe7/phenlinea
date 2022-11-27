<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Clarisa
{
  //https://pos.clarisa.co:8443/seguridad/rest/api/v1/login/
  //"https://pos.clarisa.co:8443/reportes/rest/api/v1/pdf/factura?nit={$nit}&numeroFactura={$number}"
  public static $username = 'contabilidad@phenlinea.com';
  public static $password = '1020449531icpa';
  public static $nit      = '901394830';

  public static function getToken(){
    return Cache::remember('clarisa_token', 84600, function () {
      $http = new Client(['headers'=>["Content-Type" => "application/json"]]);

      $requestLogin = $http->request(
        'POST',
        'https://csi.clarisa.co:8443/seguridad/rest/api/v1/login/',[
          "json"=>[
            "usuario"     => self::$username,
            "contrasenia" => self::$password,
          ]
        ]
      );

      $token = json_decode($requestLogin->getBody()->getContents())->data->token;
      return $token;
    });
  }

  public static function getInvoicePDF($number){
    $http  = new Client(['headers'=>["Content-Type" => "application/json"]]);
    $token = self::getToken();
    $nit = self::$nit;
    $url = "https://csi.clarisa.co:8443/reportes/rest/api/v1/pdf/factura?numeroFactura={$number}&nit={$nit}";
    
    $requestInvoice = $http->request(
      'GET',
      $url,
      [
        "headers" => [
          'Authorization' => $token,
          'Content-Type'  => 'application/json'
        ]
      ]
    );

    return $invoice = json_decode( $requestInvoice->getBody()->getContents() )->data;
  }

}
