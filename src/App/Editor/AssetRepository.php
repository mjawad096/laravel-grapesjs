<?php

namespace Dotlogics\Grapesjs\App\Editor;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AssetRepository
{
    public function getAllMediaLinks()
    {
        $allStoredMedia = Media::all()->map(function($media){
            return $media->getFullUrl();
        });

        return $allStoredMedia->toArray();
    }

    public function getUploadUrl(){
    	return route('grapesjs.editor.asset.store');
    }
}