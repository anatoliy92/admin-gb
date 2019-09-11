<?php

Route::group(['namespace' => 'Avl\AdminGb\Controllers\Admin', 'middleware' => ['web', 'admin'], 'as' => 'admingb::'], function () {

		Route::resource('sections/{id}/gb', 'GbController', ['as' => 'sections']);

});
