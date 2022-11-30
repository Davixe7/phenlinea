<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WhatsappMessagesBatch extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    
    protected $fillable = [
        'admin_id',
        'receivers_numbers',
        'message',
        'status'
    ];
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment')->singleFile();
    }
    
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
}
