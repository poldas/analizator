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
Route::get('/analiza/create', ['as' => 'analiza.create', 'uses' =>'AnalizatorController@createForm']);
Route::post('/analiza/store', ['as' => 'analiza.store', 'uses' =>'AnalizatorController@createNew']);
Route::get('/analiza/delete/{id}', ['as' => 'analiza.delete', 'uses' =>'AnalizatorController@delete']);
Route::get('/analiza/show/{id}', ['as' => 'analiza.show', 'uses' =>'AnalizatorController@show']);
Route::get('/analiza/konfiguruj/{id}', ['as' => 'analiza.konfiguruj', 'uses' =>'AnalizatorController@konfiguruj']);
Route::get('/analiza/parsuj/{id}', ['as' => 'analiza.parsuj', 'uses' =>'AnalizatorController@parsuj']);

Route::get('/analiza/lista', ['as' => 'analiza.lista', 'uses' =>'AnalizatorController@lista']);
Route::get('/analiza/wykresy', ['as' => 'analiza.wykresy', 'uses' =>'WykresyController@wykresy']);


Route::get('/analiza/obszar/delete/{id_analiza}', ['as' => 'analiza.obszar.delete', 'uses' =>'AnalizatorController@obszarDelete']);

Route::get('/analiza/uczniowie/delete/{id_analiza}', ['as' => 'analiza.uczniowie.delete', 'uses' =>'AnalizatorController@uczniowieDelete']);

Route::get('/analiza/wyniki/delete/{id_analiza}', ['as' => 'analiza.wyniki.delete', 'uses' =>'AnalizatorController@wynikiDelete']);