<?php

namespace Topdot\Grapesjs\App\Traits;

use Illuminate\Http\Request;

trait EditorTrait{

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store_gjs_data(Request $request, $model)
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
