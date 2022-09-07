<?php

namespace Dotlogics\Grapesjs\App\Editor;

class PluginManager
{
    /**
     * @var array|bool
     */
    public $basicBlocks = false;

    /**
     * @var array|bool
     */
    public $bootstrap4Blocks = false;
    
    /**
     * @var array|bool
     */
    public $codeEditor = false;
    
    /**
     * @var array|bool
     */
    public $imageEditor = false;
    
    /**
     * @var array|bool
     */
    public $templates = false;
    
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
                $enabled = true;

                if(is_string($key)){
                    $name = $key;

                    if(is_string($value)){
                        $scripts = $value;
                    }else{
                        $options = $value;
                    }
                }else if(is_string($value)){
                    $name = $value;
                }else if(!is_array($value)){
                    return null;
                }else{
                    $name = $value['name'] ?? null;
                    $options = $value['options'] ?? [];
                    $enabled = (bool)($value['enabled'] ?? true);
                    $styles = $value['styles'] ?? [];
                    $scripts = $value['scripts'] ?? [];

                    if(!empty($value['styles'])){
                    }

                    if(!empty($value['scripts'])){
                    }
                }

                $styles = array_map('url', (array)$styles);
                $scripts = array_map('url', (array)$scripts);

                return compact('name', 'options', 'enabled', 'styles', 'scripts');
            })
            ->filter()
            ->unique('name')
            ->where('enabled', true)
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
