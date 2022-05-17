<?php

namespace Dotlogics\Grapesjs\App\Editor;

class PluginManager
{
    public array|bool $basicBlocks = false;
    public array|bool $bootstrap4Blocks = false;
    public array|bool $codeEditor = false;
    public array|bool $imageEditor = false;
    public array|bool $templates = false;
    public array $customFonts = [];
    public array $pluginsLoader = [];

    function __construct($templates_url = null){
        $this->basicBlocks = config('laravel-grapesjs.plugins.default.basic_blocks', false);
        $this->bootstrap4Blocks = config('laravel-grapesjs.plugins.default.bootstrap4_blocks', false);
        $this->codeEditor = config('laravel-grapesjs.plugins.default.code_editor', false);
        $this->imageEditor = config('laravel-grapesjs.plugins.default.image_editor', false);
        $this->customFonts = config('laravel-grapesjs.plugins.default.custom_fonts', []);
        $this->templates = config('laravel-grapesjs.plugins.default.templates', false);

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

        if($this->templates){
            $this->templates = [
                'url' => $templates_url,
            ];
        }

        $this->pluginsLoader = $this->getPluginsLoaderOptions();
    }

    public function getPluginsLoaderOptions()
    {
        $config = config('laravel-grapesjs.plugins.custom', []);

        if(!is_array($config)){
            $config = [];
        }

        return collect($config)
            ->map(function($value, $key){
                $options = [];
                $styles = [];
                $scripts = [];

                if(is_string($value)){
                    $name = $value;
                }else if(!is_array($value)){
                    return null;
                }else if(is_string($key)){
                    $name = $key;
                    $options = $value;
                }else{
                    $name = $value['name'] ?? null;
                    $options = $value['options'] ?? [];

                    if(!empty($value['styles'])){
                        $styles = (array)$value['styles'] ?? [];
                    }

                    if(!empty($value['scripts'])){
                        $scripts = (array)$value['scripts'] ?? [];
                    }
                }

                $styles = array_map('url', $styles);
                $scripts = array_map('url', $scripts);

                return compact('name', 'options', 'styles', 'scripts');
            })
            ->filter()
            ->unique('name')
            ->values()
            ->toArray();
    }

    protected function getPluginStyleScript($type = 'scripts')
    {
        return collect($this->pluginsLoader)->pluck($type)->flatten()->toArray();
    }

    public function getPluginStyles()
    {
        return $this->getPluginStyleScript('styles');
    }

    public function getPluginScripts()
    {
        return $this->getPluginStyleScript();
    }
}
