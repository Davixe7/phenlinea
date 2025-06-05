<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BatchMessage extends Model implements HasMedia
{
  use HasFactory;
  use InteractsWithMedia;

  protected $guarded = ['id'];

  public function admin()
  {
    return $this->belongsTo(Admin::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('attachment')->singleFile();
  }

  public function receivers()
  {
    return $this->belongsToMany(Extension::class);
  }

  public function getBodyAttribute($value)
  {
    return $this->sanitizeText($value);
  }

  private function sanitizeText($text)
  {
    // Eliminar saltos de línea y tabulaciones
    $text = preg_replace('/[\r\n\t]+/', ' ', $text);

    // Reemplazar más de 4 espacios consecutivos por uno solo
    $text = preg_replace('/ {4,}/', ' ', $text);

    return trim($text);
  }
}
