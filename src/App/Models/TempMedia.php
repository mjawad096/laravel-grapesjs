<?php

namespace Topdot\Grapesjs\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TempMedia extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [];

    public function getFile($collection='default')
    {
        if ( !$this->hasMedia($collection) ){
            return null;
        }

        return $this->getFirstMedia($collection);
    }

    public function getImage($collection='default')
    {
        return $this->getFile($collection);
    }
}
