<?php

use Illuminate\Support\Facades\Route;


Route::prefix('laravel-grapesjs')->name('laravel-grapesjs.')->namespace('Dotlogics\Grapesjs\App\Http\Controllers')->group(function(){
	Route::post('editor/asset/store', 'AssetController@store')->name('asset.store');
	
	Route::get('editor/{model}/{editable}', 'EditorController@editor')->name('model.editor');
	Route::post('editor/{model}/{editable}', 'EditorController@store')->name('model.store');
	
	Route::get('editor/{model}/{editable}/templates', 'EditorController@templates')->name('model.templates');
	Route::get('editor/templates', 'EditorController@templates')->name('templates');
});
