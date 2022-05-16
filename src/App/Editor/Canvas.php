<?php

namespace Dotlogics\Grapesjs\App\Editor;

class Canvas
{
    public array $styles = [];
    public array $scripts = [];

    function __construct()
    {
        $this->styles = config('laravel-grapesjs.canvas.styles', []);
        $this->scripts = config('laravel-grapesjs.canvas.scripts', []);
    }

    public function mergeStyles($styles)
    {
        $this->styles = array_merge($this->styles, $styles);
        
        return $this;
    }

    public function mergeScripts($scripts)
    {
        $this->scripts = array_merge($this->scripts, $scripts);

        return $this;
    }
}
