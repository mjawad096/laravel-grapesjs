<?php

namespace Dotlogics\Grapesjs\App\Editor;

use Dotlogics\Grapesjs\App\Contracts\Editable;

class EditorFactory extends EditorBaseClass
{
    public function initialize(Editable $editable)
    {
        // dd($editable->style_sheet_links);
        $assetRepository = app(AssetRepository::class);
        $editorCanvas = new EditorCanvas;
        $editorCanvas->styles = array_merge(
            config('grapesjs.styles'), $editable->style_sheet_links
        );

        $editorCanvas->scripts = array_merge(
            config('grapesjs.scripts'), $editable->script_links
        );

        $editorStorage = new EditorStorageManager;
        $editorStorage->type = 'remote';
        $editorStorage->urlStore = $editable->store_url;
        $editorStorage->params = [
            '_token' => csrf_token()
        ];

        $editorAssetManager = new EditorAssetManager;
        $editorAssetManager->assets = $assetRepository->getAllMediaLinks();
        $editorAssetManager->upload = $assetRepository->getUploadUrl();
        $editorAssetManager->headers = [
            '_token' => csrf_token()
        ];
        $editorAssetManager->uploadName = 'file';
        $editorConfig = new EditorConfig;
        $editorConfig->components = $editable->components; 
        $editorConfig->style = $editable->styles;
        $editorConfig->canvas = $editorCanvas;
        $editorConfig->assetManager = $editorAssetManager;
        $editorConfig->storageManager = $editorStorage;
        $editorConfig->forceClass = false;
        $editorConfig->templatesUrl = $editable->templates_url;

        return $editorConfig;
    }
}
