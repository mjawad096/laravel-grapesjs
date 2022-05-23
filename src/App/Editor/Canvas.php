<?php

namespace Dotlogics\Grapesjs\App\Editor;

class Canvas
{
    public array $styles = [];
    public array $scripts = [];

    function __construct($styles = null, $scripts = null)
    {
        $this->initStylesAndScripts();

        if(!empty($styles)){
            $this->mergeStyles($styles);
        }

        if(!empty($scripts)){
            $this->mergeScripts($scripts);
        }
    }

    protected function mapScriptsUrls($urls)
    {
        $urls = collect($urls)->filter()->values()->toArray();

        return array_map('url', $urls);
    }

    protected function initStylesAndScripts()
    {
        collect(['styles', 'scripts'])
            ->each(function($type){
                $this->{$type} = $this->mapScriptsUrls(config("laravel-grapesjs.canvas.{$type}", []));
            });
    }

    public function mergeStyles($styles)
    {
        $this->styles = array_merge($this->styles, $this->mapScriptsUrls($styles));
        
        return $this;
    }

    public function mergeScripts($scripts)
    {
        $this->scripts = array_merge($this->scripts, $this->mapScriptsUrls($scripts));

        return $this;
    }
}
