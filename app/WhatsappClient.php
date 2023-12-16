<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappClient extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getMessage($body, $admin_name){
      $message = "📦Comunidad QR📦 \n\n";
      $message .= "Unidad: *{$admin_name}* \n\n";
      $message .= "Asunto: {$body} \n\n";
      $message .= "Att: Administración 👍 \n\n";
      $message .= "Servicio prestado por Phenlinea.com";
      return $message;
    }
}
