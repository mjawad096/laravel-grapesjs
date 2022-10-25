<?php

use Illuminate\Support\Facades\Route;


Route::prefix('laravel-grapesjs')
	->name('laravel-grapesjs.')
	->middleware(config('laravel-grapesjs.routes.middleware', []))
	->namespace('Dotlogics\Grapesjs\App\Http\Controllers')
	->group(function(){
		Route::post('asset/store', 'AssetController@store')->name('asset.store');
		Route::get('asset/proxy',  'AssetController@proxy')->name('asset.proxy');
		
		Route::get('{model}/{editable}', 'EditorController@editor')->name('model.editor');
		Route::post('{model}/{editable}', 'EditorController@store')->name('model.store');
		
		Route::get('{model}/{editable}/templates', 'EditorController@templates')->name('model.templates');
		Route::get('templates', 'EditorController@templates')->name('templates');
	});
