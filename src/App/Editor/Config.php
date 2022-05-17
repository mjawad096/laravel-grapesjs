<?php

namespace Dotlogics\Grapesjs\App\Editor;

use Dotlogics\Grapesjs\App\Contracts\Editable;

class Config
{
    public bool $exposeApi = false;
    public bool $imageEditor = false;
    public array $fonts = [];
    public string $container = '#editor';
    public bool $fromElement = false;
    public string $height = "100vh";
    public string $width = '100%';
    public ?StorageManager $storageManager;
    public ?AssetManager $assetManager;
    public array $components;
    public array $style;
    public Canvas $canvas;
    public ?string $templatesUrl;
    public bool $forceClass = true;

    public $dist_path;
    public $media_proxy_url;
    public $media_proxy_url_input;

    function __construct(){
        $this->exposeApi = config('laravel-grapesjs.expose_api', false);
        $this->imageEditor = config('laravel-grapesjs.image_editor', false);
        $this->forceClass = config('laravel-grapesjs.force_class', false);
        $this->fonts = config('laravel-grapesjs.fonts', []);

        $this->dist_path = asset('vendor/laravel-grapesjs');

        $this->media_proxy_url = route('laravel-grapesjs.asset.proxy');
        $this->media_proxy_url_input = 'file';
    }

    public function initialize(Editable $editable)
    {
        $assetManager = app(AssetManager::class);
        $storageManager = app(StorageManager::class, ['save_url' => $editable->store_url]);

        $canvas = app(Canvas::class)
            ->mergeStyles($editable->style_sheet_links)
            ->mergeScripts($editable->script_links);
        

        $this->assetManager = $assetManager;
        $this->canvas = $canvas;
        $this->storageManager = $storageManager;


        $this->components = $editable->components; 
        $this->style = $editable->styles;
        $this->templatesUrl = $editable->templates_url;

        return $this;
    }

    public function toJson()
    {
        return json_encode($this);
    }
    
    public function __toString()
    {
        return $this->toJson();
    }

    public function toArray()
    {
    	return json_decode($this->toJson(), true);
    }
}
