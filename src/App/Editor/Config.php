<?php

namespace Dotlogics\Grapesjs\App\Editor;

use Dotlogics\Grapesjs\App\Contracts\Editable;

class Config
{
    // General
    public bool $exposeApi = false;
    public string $container = '#editor';
    public bool $fromElement = false;
    public string $height = "100vh";
    public string $width = '100%';
    public bool $forceClass = true;

    //Default Content
    public array $components;
    public array $style;
    public ?string $templatesUrl;

    // Management
    public Canvas $canvas;
    public PluginManager $pluginManager;
    public ?StorageManager $storageManager;
    public ?AssetManager $assetManager;

    public array $fonts = [];

    function __construct(){
        $this->exposeApi = config('laravel-grapesjs.expose_api', false);
        $this->forceClass = config('laravel-grapesjs.force_class', false);
        $this->fonts = config('laravel-grapesjs.fonts', []);
    }

    public function initialize(Editable $editable)
    {
        $pluginManager = app(PluginManager::class);
        $assetManager = app(AssetManager::class);
        $storageManager = app(StorageManager::class, ['save_url' => $editable->store_url]);

        $canvas = app(Canvas::class)
            ->mergeStyles($editable->style_sheet_links)
            ->mergeScripts($editable->script_links);
        

        $this->pluginManager = $pluginManager;
        $this->assetManager = $assetManager;
        $this->canvas = $canvas;
        $this->storageManager = $storageManager;


        $this->components = $editable->components; 
        $this->style = $editable->styles;
        $this->templatesUrl = $editable->templates_url;

        // dd($this->toArray());
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
