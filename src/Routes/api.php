<?php

use Illuminate\Support\Str;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Middleware\ApiRequest;

$api_route_prefix = \config('badaso.api_route_prefix');
Route::group(['prefix' => $api_route_prefix, 'namespace' => 'Uasoft\Badaso\Module\Lms\Controllers', 'as' => 'badaso.', 'middleware' => [ApiRequest::class]], function () {
    Route::group(['prefix' => 'module/lms/v1'], function () {
        //Route for Category
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'CategoriesController@index');
            Route::post('/add', 'CategoriesController@add');
            Route::get('/read', 'CategoriesController@read');
            Route::put('/edit', 'CategoriesController@edit');
            Route::get('/delete', 'CategoriesController@delete');
        });

        //Route for Course
        Route::group(['prefix' => 'course'], function () {
            Route::get('/', 'CoursesController@index');
            Route::post('/add', 'CoursesController@add');
            Route::get('/read', 'CoursesController@read');
            Route::put('/edit', 'CoursesController@edit');
            Route::delete('/delete', 'CoursesController@destroy');
        });
    });
});
