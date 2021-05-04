<?php

namespace Topdot\Grapesjs\Editor;


class EditorFactory extends EditorBaseClass
{
    public function initialize(Editable $editable)
    {

        $editorCanvas = new EditorCanvas;
        $editorCanvas->styles = array_merge(
            config('editor.styles'),$editable->getStyleSheetLinks()
        );

        $editorCanvas->scripts = array_merge(
            config('editor.scripts'),$editable->getScriptLinks()
        );

        $editorStorage = new EditorStorageManager;
        $editorStorage->type = 'remote';
        $editorStorage->urlStore = $editable->getStoreUrl();
        $editorStorage->params = [
            '_token' => csrf_token()
        ];

        $editorAssetManager = new EditorAssetManager;
        $editorAssetManager->assets = (new AssetRepository)->getAllMediaLinks();
        $editorAssetManager->upload = route('editor.asset.store');
        $editorAssetManager->headers = [
            '_token' => csrf_token()
        ];
        $editorAssetManager->uploadName = 'file';
        $editorConfig = new EditorConfig;
        $editorConfig->components = $editable->getComponents(); 
        $editorConfig->style = $editable->getStyles();
        $editorConfig->canvas = $editorCanvas;
        $editorConfig->assetManager = $editorAssetManager;
        $editorConfig->storageManager = $editorStorage;
        $editorConfig->forceClass = false;
        $editorConfig->templatesUrl = $editable->getTemplatesUrl();

        return $editorConfig;
    }
}
