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

Route::get('/landing', ['as' => 'main', 'uses' =>'LandingPageController@index']);



Route::get('/budget', ['as' => 'budget.index', 'uses' =>'BudgetController@index']);




Route::get('/budget', ['as' => 'budget.index', 'uses' =>'BudgetController@index']);



Route::get('/mieszkania', ['as' => 'mieszkania.index', 'uses' =>'MieszkaniaController@index']);



/* Routing do projektu Analizator */
Route::get('/analiza/create', ['as' => 'analiza.create', 'uses' =>'AnalizatorController@create']);
Route::post('/analiza/store', ['as' => 'analiza.store', 'uses' =>'AnalizatorController@createNewAnaliza']);
Route::get('/analiza/delete/{id}', ['as' => 'analiza.delete', 'uses' =>'AnalizatorController@deleteAnaliza']);
Route::get('/analiza/show/{id}', ['as' => 'analiza.show', 'uses' =>'AnalizatorController@showAnaliza']);
Route::get('/analiza/konfiguruj/{id}', ['as' => 'analiza.konfiguruj', 'uses' =>'AnalizatorController@konfigurujAnaliza']);

Route::get('/analiza/przegladaj', ['as' => 'analiza.przegladaj', 'uses' =>'AnalizatorController@przegladaj']);
Route::get('/analiza/zarzadzaj', function () {
    return view('analiza.zarzadzaj');
});
Route::get('/analiza/wykresy', ['as' => 'analiza.wykresy', 'uses' =>'AnalizatorController@wykresy']);