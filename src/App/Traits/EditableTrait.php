<?php

namespace Topdot\Grapesjs\App\Traits;

trait EditableTrait{
	public $placeholders = [];

	protected function getModelClass($slugify = false): string
	{
		return $slugify ? str_replace('\\', '-', static::class) : static::class;
	}

	protected function getKeyValue(){
		return $this->{$this->getKeyName()};
	}

    protected function setGjsDataAttribute($value)
    {
        $this->attributes['gjs_data'] = json_encode($value);
    }

    protected function getGjsDataAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    protected function getHtmlAttribute()
    {
        $processedContent = $this->gjs_data['html'] ?? '';

        if ( empty($processedContent) ){
            return '';
        }

        foreach($this->getPlaceholders() as $placeolder => $replaceContent){
            $processedContent = str_replace($placeolder, $replaceContent, $processedContent);
        }

        return $processedContent;
    }

    protected function getComponentsAttribute()
    {
        return json_decode($this->gjs_data['components'] ?? '[]');
    }

    protected function getStylesAttribute()
    {
        return json_decode($this->gjs_data['styles'] ?? '[]');
    }

    protected function getStylesAttribute()
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

    public function getCssAttribute() :string
    {
        return '';
    }

    public function getAssetsAttribute() :array
    {
        return [];
    }

    public function getStoreUrlAttribute(): string
    {
        return route('grapesjs.editor.model.store', $this->getModelClass(true), $this->idgetKeyValue());
    }

    public function getTemplatesUrlAttribute(): string | null
    {
        return route('grapesjs.editor.model', $this->getModelClass(true), $this->getKeyValue());
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