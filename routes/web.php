<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('fields', 'Controller@fields');
Route::post('generate', 'Controller@generate');

Route::get('test', 'Controller@test');
Route::post('perform-test', 'Controller@performTest');
