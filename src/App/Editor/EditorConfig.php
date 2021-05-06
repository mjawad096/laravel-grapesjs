<?php

namespace Topdot\Grapesjs\App\Editor;

class EditorConfig extends EditorBaseClass
{
    public string $container = '#editor';
    public bool $fromElement = false;
    public string $height = "100vh";
    public string $width = '100%';
    public ?EditorStorageManager $storageManager;
    public ?EditorAssetManager $assetManager;
    public array | string $components;
    public array $style;
    public EditorCanvas $canvas;
    public ?string $templatesUrl;
    public bool $forceClass=true;
}
