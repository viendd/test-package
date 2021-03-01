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
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('category', 'CategoryCrudController');
    Route::get('getCategoryByLanguage', 'CategoryCrudController@getCategoryByLanguage')->name('category.getCategoryByLanguage');
    Route::crud('author', 'AuthorCrudController');
    Route::crud('historytransactiontoken', 'HistoryTransactionTokenCrudController');
    Route::crud('article', 'ArticleCrudController');
    Route::get('article/{id}/approve', 'ArticleCrudController@approve');
    Route::get('article/{id}/reject', 'ArticleCrudController@reject');
    Route::crud('tag', 'TagCrudController');
    Route::crud('dashboard', 'DashboardCrudController');
}); // this should be the absolute last line of this file
Route::get('api/category', 'App\Http\Controllers\Api\CategoryController@index');
