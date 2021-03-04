<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Modules\Article\Controllers',
], function () { // custom admin routes
    Route::crud('article', 'ArticleCrudController');
    Route::crud('articlevideo', 'ArticleVideoCrudController');
    Route::get('article/{id}/approve', 'ArticleCrudController@approve');
    Route::get('articlevideo/{id}/approve', 'ArticleCrudController@approve');
    Route::get('article/{id}/reject', 'ArticleCrudController@reject');
    Route::get('articlevideo/{id}/reject', 'ArticleCrudController@reject');

}); // this should be the absolute last line of this file

