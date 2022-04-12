<?php

namespace Dotlogics\Grapesjs\App\Editor;

class EditorConfig extends EditorBaseClass
{
    public bool $exposeApi = false;
    public bool $imageEditor = false;
    public array $fonts = [];
    public string $container = '#editor';
    public bool $fromElement = false;
    public string $height = "100vh";
    public string $width = '100%';
    public ?EditorStorageManager $storageManager;
    public ?EditorAssetManager $assetManager;
    public array $components;
    public array $style;
    public EditorCanvas $canvas;
    public ?string $templatesUrl;
    public bool $forceClass = true;

    function __construct(){
        $this->exposeApi = config('grapesjs.expose_api', false);
        $this->imageEditor = config('grapesjs.image_editor', false);
        $this->forceClass = config('grapesjs.force_class', false);
        $this->fonts = config('grapesjs.fonts', []);
    }
}
