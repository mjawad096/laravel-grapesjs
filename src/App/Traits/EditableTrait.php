<?php

namespace Dotlogics\Grapesjs\App\Traits;

use DOMDocument;

trait EditableTrait{
    public $placeholders = [];

    protected function getModelClass($slugify = false): string
    {
        return $slugify ? str_replace('\\', '-', static::class) : static::class;
    }

    protected function getModelBaseClass(){
        $explode = explode('\\', $this->getModelClass()) ?? ['Item'];
        return end($explode);
    }

    protected function getKeyValue()
    {
        return $this->{$this->getKeyName()};
    }

    public function getEditorPageTitleAttribute(): string
    {
        $title = $this->name ?: $this->title ?: $this->slug ?: '';

        $title = "{$title} " . $this->getModelBaseClass();

        return $title;
    }

    public function setGjsDataAttribute($value)
    {
        $this->attributes['gjs_data'] = json_encode($value);
    }

    public function getGjsDataAttribute($value): array
    {
        return json_decode($value, true) ?? [];
    }

    protected function getPlaceholderAttributes($placeholder)
    {
        $attributes = ['item' => $this];
        try {
            $placeholder = html_entity_decode($placeholder);
            $dom = new DOMDocument;
            libxml_use_internal_errors(TRUE);
            $dom->loadHTML("<$placeholder />");
            libxml_use_internal_errors(FALSE);
            
            $body = $dom->documentElement->firstChild;
            $placeholder = $body->childNodes[0];
            $length = $placeholder->attributes->length;
            
            for ($i = 0; $i < $length; ++$i) {
                $name = $placeholder->attributes->item($i)->name;
                $value = $placeholder->getAttribute($name);

                if(empty($value) || $value == "''"){
                    $value = true;
                }

                $attributes[$name] = $value;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $attributes;
    }

    protected function findAndSetPlaceholders($html){
        $re = '/\[\[[A-Z][a-z]*(-[A-Z][a-z]*)*([\s]+[a-z]+(=[^]]+)?)*\]\]/';

        preg_match_all($re, $html, $placeholders);

        $placeholders = $placeholders[0] ?? [];

        foreach ($placeholders as $_placeholder) {
            if(empty($this->placeholders[$_placeholder])){
                $placeholder = str_replace(['[[', ']]'], '', $_placeholder);                
                $attributes = $this->getPlaceholderAttributes($placeholder);

                $view = preg_split('/[\s]+/', $placeholder);
                $view = array_shift($view);
                $view = strtolower($view);

                if(view()->exists("laravel-grapesjs::placeholders.{$view}")){
                    $this->setPlaceholder($_placeholder, view("laravel-grapesjs::placeholders.{$view}", $attributes)->render());
                }
            }
        }

        $placeholders = $this->getPlaceholders();
        $html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

        return $html;
    }

    public function getHtmlAttribute(): string
    {
        $html = $this->gjs_data['html'] ?? '';

        if ( empty($html) ){
            return '';
        }

        return $this->findAndSetPlaceholders($html);
    }

    public function getCssAttribute() : string
    {
        return $this->gjs_data['css'] ?? '';
    }

    public function getComponentsAttribute() : array
    {
        return json_decode($this->gjs_data['components'] ?? '[]');
    }

    public function getStylesAttribute(): array
    {
        return json_decode($this->gjs_data['styles'] ?? '[]');
    }

    public function getStyleSheetLinksAttribute(): array
    {
        return [];
    }

    public function getScriptLinksAttribute(): array
    {
        return [];
    }

    public function getAssetsAttribute() :array
    {
        return [];
    }

    public function getStoreUrlAttribute(): string
    {
        return route('laravel-grapesjs.model.store', [$this->getModelClass(true), $this->getKeyValue()]);
    }

    public function getTemplatesUrlAttribute(): ?string
    {
        return route('laravel-grapesjs.model.templates', [$this->getModelClass(true), $this->getKeyValue()]);
    }

    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    public function setPlaceholder($placeolder, $content)
    {
        $this->placeholders[$placeolder] = $content;

        return $this;
    }
}