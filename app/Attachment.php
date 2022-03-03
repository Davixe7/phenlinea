<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
  protected $fillable = ['url', 'path', 'product_id', 'classified_id', 'visit_id'];

  public static function createAll($attachments, $foreign_key, $id){
    foreach($attachments as $file){
      Attachment::create([
        $foreign_key => $id,
        'url'        => $file['url'],
        'path'       => $file['path'],
      ]);
    }
  }
}
