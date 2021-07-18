<?php

use Illuminate\Support\Facades\Route;


Route::prefix(config('grapesjs.route_path'))->name(config('grapesjs.route_name'))->group([
    'namespace' => 'Topdot\Grapesjs\App\Http\Controllers',
    'middleware' => config('grapesjs.middleware')
], function(){
	Route::post('editor/asset/store', 'AssetController@store')->name('editor.asset.store');
	
	Route::get('editor/{model}/{editable}', 'EditorController@editor')->name('editor.model.editor');
	Route::post('editor/{model}/{editable}', 'EditorController@store')->name('editor.model.store');
	
	Route::get('editor/{model}/{editable}/templates', 'EditorController@templates')->name('editor.model.templates');
	Route::get('editor/templates', 'EditorController@templates')->name('editor.templates');
});
