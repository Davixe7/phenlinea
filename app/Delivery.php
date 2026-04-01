<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Delivery extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('picture')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('original')
            ->fit(Manipulations::FIT_MAX, 500, 500) // no excede 500x500
            ->performOnCollections('picture');

        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 60, 60) // thumbnail exacto
            ->performOnCollections('picture');
    }

    public function extension()
    {
        return $this->belongsTo(Extension::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Extension::class, 'extension_id');
    }
}
