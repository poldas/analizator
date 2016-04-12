<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mieszkania', function () {
    return view('mieszkania.mieszkania');
});

Route::get('/analiza/create', ['as' => 'analiza.create', 'uses' =>'AnalizatorController@create']);
Route::post('/analiza/store', ['as' => 'analiza.store', 'uses' =>'AnalizatorController@createNewAnaliza']);
Route::get('/analiza/delete/{id}', ['as' => 'analiza.delete', 'uses' =>'AnalizatorController@deleteAnaliza']);
Route::get('/analiza/show/{id}', ['as' => 'analiza.show', 'uses' =>'AnalizatorController@showAnaliza']);

Route::get('/analiza/przegladaj', ['as' => 'analiza.przegladaj', 'uses' =>'AnalizatorController@przegladaj']);
Route::get('/analiza/zarzadzaj', function () {
    return view('analiza.zarzadzaj');
});
Route::get('/analiza/wykresy', function () {
    return view('analiza.wykresy');
});