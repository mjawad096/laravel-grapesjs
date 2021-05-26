<?php

namespace Topdot\Grapesjs\App\Traits;

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

    protected function findAndSetPlaceholders($html){
        // $re = '/\[\[([A-Z]([a-z]+)?-?)+\]\]/';

        //included attributes
        # $re = '/\[\[[A-Z][a-z]*(-[A-Z][a-z]*)*([\s][a-z]+=.+)*\]\]/';
        $re = '/\[\[[A-Z][a-z]*(-[A-Z][a-z]*)*([\s]+[a-z]+(=.+)?)*\]\]/';

        preg_match_all($re, $html, $placeholders);

        $placeholders = $placeholders[0] ?? [];


        foreach ($placeholders as $_placeholder) {
            if(empty($this->placeholders[$_placeholder])){
                $placeholder = str_replace(['[[', ']]'], '', $_placeholder);
                $placeholder_options = preg_split('/[\s]+/', $placeholder);

                $view = array_shift($placeholder_options);
                $view = strtolower($view);

                $attributes = ['item' => $this];
                foreach($placeholder_options as $attribute){
                    $attribute = explode('=', $attribute);
                    
                    $name = $attribute[0];
                    
                    if(!empty($attribute[1])){
                        $value = str_replace(['"', "'"], '', $attribute[1]);
                    }else{
                        $value = true;
                    }
                    
                    $attributes[$name] = $value;
                }

                if(view()->exists("grapesjs::placeholders.{$view}")){
                    $this->setPlaceholder($_placeholder, view("grapesjs::placeholders.{$view}", $attributes)->render());
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
        return route('grapesjs.editor.model.store', [$this->getModelClass(true), $this->getKeyValue()]);
    }

    public function getTemplatesUrlAttribute(): ?string
    {
        return route('grapesjs.editor.model.templates', [$this->getModelClass(true), $this->getKeyValue()]);
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