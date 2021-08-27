<?php

namespace Dotlogics\Grapesjs\App\Contracts;

interface Editable{
    public function getEditorPageTitleAttribute(): string;

    public function setGjsDataAttribute($value);
    public function getGjsDataAttribute($value): array;

    public function getStyleSheetLinksAttribute(): array;
    public function getScriptLinksAttribute(): array;

    public function getComponentsAttribute() : array;
    public function getStylesAttribute() :array;

    public function getHtmlAttribute() :string;
    public function getCssAttribute() :string;
    public function getAssetsAttribute() :array;

    public function getStoreUrlAttribute(): string;
    public function getTemplatesUrlAttribute(): ?string;
}