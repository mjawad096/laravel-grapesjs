<?php

namespace Topdot\Grapesjs\App\Editor;


class EditorStorageManager
{
    public string $id = 'laravel-editor';           // Prefix identifier that will be used on parameters
    public string $type = 'remote';        // Type of the storage
    public bool $autosave = true;         // Store data automatically
    public bool $autoload = false;         // Store data automatically
    public ?string $urlStore = null;
    public $params = [
        '_token' => null 
    ];
    // autoload: true,         // Autoload stored data on init
    public int $stepsBeforeSave = 10;  
}
