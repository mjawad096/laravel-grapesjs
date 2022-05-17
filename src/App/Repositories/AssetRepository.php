<?php

namespace Dotlogics\Grapesjs\App\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

class AssetRepository
{
    protected $diskPath;
    protected $storage;

    public function __construct()
    {
        $this->storage = Storage::disk(config('laravel-grapesjs.assets.disk'));
        $this->diskPath = config('laravel-grapesjs.assets.path') ?? 'laravel-grapesjs/media';
    }

    public function getAllMediaLinks()
    {
        return collect($this->storage->allFiles($this->diskPath))
            ->map(fn ($file) => $this->storage->url($file))
            ->toArray();
    }

    public function getUploadUrl()
    {
        return config('laravel-grapesjs.assets.upload_url') ?? route('laravel-grapesjs.asset.store');
    }

    public function uploadSinglgeFile(UploadedFile $file)
    {
        /**
         * Check if file is submitted by Image Editor Its name will be blob
         */
        if ( 'blob' ==  $file->getClientOriginalName()){
            $path = $this->storage->putFile($this->diskPath, $file, 'public');
        }else{
            $path = $this->storage->putFileAs($this->diskPath, $file, $file->getClientOriginalName(), 'public');
        }

        return $this->storage->url($path);
    }

    public function uploadFilesFromRequest($file_name = 'file')
    {
        $files = request()->file($file_name);

        if (!is_array($files)){
            $files = [$files];
        }
        
        $uploaded_files = [];

        foreach ($files as $file) {
            $uploaded_files[] = $this->uploadSinglgeFile($file);
        }

        return $uploaded_files;
    }
}
