<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', ['middleware' => 'guest', 'uses' => 'RecordsController@index']);
Route::get('/autocomplete', ['middleware' => 'guest', 'uses' => 'RecordsController@autocomplete']);
Route::get('/search', ['middleware' => 'guest', 'uses' => 'RecordsController@search']);
Route::post('/import', ['middleware' => 'guest', 'uses' => 'RecordsController@import']);
Route::post('/download', ['middleware' => 'guest', 'uses' => 'RecordsController@downloadFile']);