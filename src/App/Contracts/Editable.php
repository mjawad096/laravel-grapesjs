<?php

namespace Topdot\Grapesjs\App\Contracts;

interface Editable{


    public function getStyleSheetLinks(): array;
    public function getScriptLinks(): array;

    public function getComponents() : array | string;
    public function getStyles() :array;

    public function getHtml() :string;
    public function getCss() :string;
    public function getAssets() :array;

    public function getStoreUrl(): string;
    public function getTemplatesUrl(): string | null;
}