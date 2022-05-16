<?php

namespace Dotlogics\Grapesjs\App\Editor;

use Dotlogics\Grapesjs\App\Contracts\Editable;

class Factory extends Base
{
    public function initialize(Editable $editable)
    {
        $assetRepository = app(AssetRepository::class);
        $editorCanvas = new EditorCanvas;     
        
        $editorAssetManager = new EditorAssetManager;
        

        $editorConfig = new EditorConfig;
        $editorConfig->assetManager = $editorAssetManager;
        $editorConfig->canvas = with(new EditorCanvas)->mergeStyles($editable->style_sheet_links)->mergeScripts($editable->script_links);
        $editorConfig->storageManager = new EditorStorageManager($editable->store_url);


        $editorConfig->components = $editable->components; 
        $editorConfig->style = $editable->styles;
        $editorConfig->templatesUrl = $editable->templates_url;

        return $editorConfig;
    }
}
