<?php

namespace App\Listeners;

use App\Events\CreatedUpdatedPhone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendSMSNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreatedUpdatedPhone  $event
     * @return void
     */
    public function handle(CreatedUpdatedPhone $event)
    {
      $url = 'https://api.hablame.co/sms/envio/';

      $numero = $event->extension->admin->phone;
      $phone = ( $event->line == 1 ) ? $event->extension->phone_1 : $event->extension->phone_2;

      if( $event->extension->admin->phone_2 ){
        $numero .= ',' . $event->extension->admin->phone_2;
      }

      $key    = $event->extension->admin->key;
      $apto   = $event->extension->name;
      $phone  = ($phone) ?: '0000';
      $dial   = $event->line;
      $sms    = '#PWD' . $key . '#' . $apto . ':DIAL0' . $dial . '=' . $phone;
      // #PWD123456#101:DIAL01=3144379170

      $data = [
        'cliente'    => 10014170, //Numero de cliente
        'api'        => '8iBO6Afeuva7yqUj447SmmARNatHtQ', //Clave API suministrada
        'numero'     => $numero, //numero o numeros telefonicos a enviar el SMS (separados por una coma ,)
        'sms'        => $sms, //Mensaje de texto a enviar
        'fecha'      => '', //(campo opcional) Fecha de envio, si se envia vacio se envia inmediatamente (Ejemplo: 2017-12-31 23:59:59)
        'referencia' => 'PHEnlinea', //(campo opcional) Numero de referencio ó nombre de campaña
      ];

      $options = [
        'http' => [
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($data)
        ]
      ];

      $context  = stream_context_create($options);
      $result   = json_decode( (file_get_contents($url, false, $context) ), true);

      if ($result["resultado"]===0) {
        Storage::disk('local')->append('sms_log.txt', now() . $sms . '\n', true);
      }else {
        Storage::disk('local')->put('sms_log.txt', now() . '_ Error al enviar sms ' . $sms . '\n', true);
      }
    }
}
