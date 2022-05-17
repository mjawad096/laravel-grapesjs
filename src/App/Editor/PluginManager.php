<?php

namespace Dotlogics\Grapesjs\App\Editor;

class PluginManager
{
    public array|bool $basicBlocks = true;
    public array|bool $bootstrap4Blocks = false;
    public array|bool $imageEditor = false;

    function __construct(){
        $this->basicBlocks = config('laravel-grapesjs.plugins.default.basic_blocks', true);
        $this->bootstrap4Blocks = config('laravel-grapesjs.plugins.default.bootstrap4_blocks', false);
        $this->imageEditor = config('laravel-grapesjs.plugins.default.image_editor', false);

        if($this->basicBlocks){
            $this->basicBlocks = [];
        }

        if($this->bootstrap4Blocks){
            $this->bootstrap4Blocks = [];
        }

        if($this->imageEditor){
            $this->imageEditor = [
                'dist_path' => asset('vendor/laravel-grapesjs'),
                'proxy_url' => route('laravel-grapesjs.asset.proxy'),
                'proxy_url_input' => 'file',
            ];
        }
    }
}
