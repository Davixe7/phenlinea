<?php

namespace App\Http\Controllers;

use App\BatchMessage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BatchMessageController extends Controller implements HasMedia
{
  use InteractsWithMedia;

  function index()
  {
    return BatchMessage::all();
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('attachment')->singleFile();
  }

  public function admin()
  {
    return $this->belongsTo('App\Admin');
  }
}
