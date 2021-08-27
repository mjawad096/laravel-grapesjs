<?php

namespace Dotlogics\Grapesjs\App\Traits;

use Illuminate\Http\Request;
use Dotlogics\Grapesjs\App\Editor\EditorFactory;

trait EditorTrait{

	protected function show_gjs_editor(Request $request, $model){
		$factory = app(EditorFactory::class);
		$editorConfig = $factory->initialize($model);
		return view('grapesjs::edittor', compact('editorConfig', 'model'));
	}

	protected function store_gjs_data(Request $request, $model)
	{	
		$model->gjs_data = [
	        'components' => $request->get('laravel-editor-components'),
	        'styles' => $request->get('laravel-editor-styles'),
	        'css' => $request->get('laravel-editor-css'),
	        'html' => $request->get('laravel-editor-html'),
	    ];

	    $model->save();

	    return response()->noContent(200);
	}
}
