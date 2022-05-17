<?php

namespace Dotlogics\Grapesjs\App\Editor;

class PluginManager
{
    public array|bool $basicBlocks = false;
    public array|bool $bootstrap4Blocks = false;
    public array|bool $codeEditor = false;
    public array|bool $imageEditor = false;
    public array $customFonts = [];

    function __construct(){
        $this->basicBlocks = config('laravel-grapesjs.plugins.default.basic_blocks', false);
        $this->bootstrap4Blocks = config('laravel-grapesjs.plugins.default.bootstrap4_blocks', false);
        $this->codeEditor = config('laravel-grapesjs.plugins.default.code_editor', false);
        $this->imageEditor = config('laravel-grapesjs.plugins.default.image_editor', false);
        $this->customFonts = config('laravel-grapesjs.plugins.default.custom_fonts', []);

        if($this->basicBlocks){
            $this->basicBlocks = [];
        }

        if($this->bootstrap4Blocks){
            $this->bootstrap4Blocks = [];
        }

        if($this->codeEditor){
            $this->codeEditor = [];
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
