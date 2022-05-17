<?php

namespace Dotlogics\Grapesjs\App\Editor;

use Dotlogics\Grapesjs\App\Contracts\Editable;

class Config
{
    //Global styles and scripts
    public array $styles = [];
    public array $scripts = [];

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

    // Management
    public Canvas $canvas;
    public PluginManager $pluginManager;
    public ?StorageManager $storageManager;
    public ?AssetManager $assetManager;

    function __construct(){
        $this->exposeApi = config('laravel-grapesjs.expose_api', false);
        $this->forceClass = config('laravel-grapesjs.force_class', false);
    }

    public function initialize(Editable $editable)
    {
        $pluginManager = app(PluginManager::class, ['templates_url' => $editable->templates_url]);
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

        $this->initStylesAndScripts();
        
        // dd($this->toArray());
        return $this;
    }
    
    protected function initStylesAndScripts()
    {
        collect(['styles', 'scripts'])
            ->each(function($type){
                $items = config("laravel-grapesjs.{$type}", []);
                $items = collect($items)->filter()->values()->toArray();

                $this->{$type} = array_map('url', $items);
            });
    }

    public function getStyles()
    {
        $extraStyles = $this->pluginManager ? $this->pluginManager->getPluginStyles() : [];
        
        return [...$extraStyles, ...$this->styles];
    }

    public function getScripts()
    {
        $extraScripts = $this->pluginManager ? $this->pluginManager->getPluginScripts() : [];
        
        return [...$extraScripts, ...$this->scripts];
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
