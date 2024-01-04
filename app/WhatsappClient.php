<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappClient extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getVisitorTemplate($visit){
      $message = "*CONTROL DE VISITANTES* \n\n";
      $message .= "Facial✅-QR✅-Clave Temporal✅ \n\n";
      $message .= "🏢UNIDAD:  *{$visit->admin->name}* \n";
      $message .= "🕒VALIDO PARA *1 INGRESO* \n";
      $message .= "🔢CLAVE TEMPORAL:  *{$visit->password}* \n\n";
      $message .= "Servicio prestado por PHEnlinea.com";
      return $message;
    }

    public static function getCommunityTemplate($message){
      $template = "📦Comunidad QR📦 \n\n";
      $template .= "Unidad: *{$message->admin->name}* \n\n";
      $template .= "Asunto: {$message->message} \n\n";
      $template .= "Att: Administración 👍 \n\n";
      $template .= "Servicio prestado por Phenlinea.com";
      return $template;
    }
    
    public static function getBatchTemplate($message){
      $template = "*Unidad: *{$message->admin}* \n\n";
      $template .= "{$message->message} \n\n";
      $template .= "Servicio prestado por PHenlinea.com";
      return $template;
    }

    public static function getPqrsTemplate($pqrs){
      $petitionId = str_pad($pqrs->id, 4, '0', STR_PAD_LEFT);
      $statuses   = ['pending'=>'pendiente', 'read'=>'Leído', 'replied'=>'Respuesta enviada'];
      $status     = $statuses[ $pqrs->status ];
      $link       = route('pqrs.show', ['petition'=>$pqrs]);
  
      $message = "Unidad: *{$pqrs->admin->name}* \n\n";
      $message .= "Su PQRS ha sido actualizado con éxito. Y su código de seguimiento es el *{$petitionId}* \n\n";
      $message .= "Estado: *{$status}* \n\n";
      $message .= "Link de seguimiento del estado: {$link} \n\n";
      $message .= "Servicio prestado por PHenlinea.com";
      return $message;
    }
  }
