<?php

namespace Topdot\Grapesjs\Editor;

use Illuminate\Http\Request;
use Modules\Media\Models\TempMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AssetRepository
{
    public function getAllMediaLinks()
    {
        $allStoredMedia = Media::all()->map(function($media){
            return route('api.medias.show',$media);
        });

        return $allStoredMedia->toArray();
    }
}