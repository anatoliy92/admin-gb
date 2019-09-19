<?php

Route::group(['namespace' => 'Avl\AdminGb\Controllers\Admin', 'middleware' => ['web', 'admin'], 'as' => 'admingb::'], function () {

		Route::resource('sections/{id}/gb', 'GbController', ['as' => 'sections']);

});


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localizationRedirect']], function() {
	Route::group(['namespace' => 'Avl\AdminGb\Controllers\Site'], function() {
		Route::resource('gb/{alias}/', 'GbController');
	});
});
