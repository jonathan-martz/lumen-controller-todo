<?php

use Illuminate\Support\Facades\Route;

/**
 * @todo replacec delete with delete call and so on
 */
Route::post('/user/todo/autocomplete', [
    'middleware' => ['xss', 'https'],
    'uses' => 'App\Http\Controllers\TodoController@autocomplete'
]);

Route::post('/user/todo/delete', [
    'middleware' => ['xss', 'https'],
    'uses' => 'App\Http\Controllers\TodoController@delete'
]);

Route::post('/user/todo/edit', [
    'middleware' => ['xss', 'https'],
    'uses' => 'App\Http\Controllers\TodoController@edit'
]);

Route::post('/user/todo/add', [
    'middleware' => ['xss', 'https'],
    'uses' => 'App\Http\Controllers\TodoController@add'
]);

Route::post('/user/todo/view', [
    'middleware' => ['xss', 'https'],
    'uses' => 'App\Http\Controllers\TodoController@view'
]);

Route::post('/user/todo/select', [
    'middleware' => ['xss', 'https'],
    'uses' => 'App\Http\Controllers\TodoController@select'
]);
