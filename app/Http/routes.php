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

Route::get('redirect', function() {
    redirect('done');
});
Route::get('done', function() {
return 'redirection complete';
});

//Route::resource('analiza', 'Analiza');
Route::get('/landing', ['as' => 'main', 'uses' =>'LandingPageController@index']);
Route::get('/', ['as' => 'index', 'uses' =>'HomeController@index']);
Route::get('/home', ['as' => 'home', 'uses' =>'HomeController@home']);


Route::get('/wykresy/{id_analiza}', ['as' => 'analiza.wykresyapi', 'uses' =>'WykresyController@wykresyapi']);
Route::get('/wykresy/lista/{id_analiza}', ['as' => 'analiza.wykresy', 'uses' =>'WykresyController@wykresy']);
Route::post('/wykresy/{id_wykres}', ['as' => 'analiza.wykresy.save', 'uses' =>'WykresyController@save']);


/* Routing do projektu Analizator */
Route::get('/analiza/create', ['as' => 'analiza.create', 'uses' =>'AnalizatorController@createForm']);
Route::post('/analiza/new', ['as' => 'analiza.new', 'uses' =>'AnalizatorController@create']);
Route::get('/analiza/konfiguruj/{id}', ['as' => 'analiza.konfiguruj', 'uses' =>'AnalizatorController@konfiguruj']);

Route::get('/analiza/parsuj/{id}', ['as' => 'analiza.parsuj', 'uses' =>'AnalizatorController@parsuj']);
Route::post('/analiza/parsuj/{id}', ['as' => 'analiza.parsuj', 'uses' =>'AnalizatorController@save']);

Route::get('/analiza/delete/{id}', ['as' => 'analiza.delete', 'uses' =>'AnalizatorController@delete']);
Route::get('/analiza/show/{id}', ['as' => 'analiza.show', 'uses' =>'AnalizatorController@show']);
Route::get('/analiza/lista', ['as' => 'analiza.lista', 'uses' =>'AnalizatorController@lista']);


Route::get('/analiza/obszar/delete/{id_analiza}', ['as' => 'analiza.obszar.delete', 'uses' =>'AnalizatorController@obszarDelete']);
Route::get('/analiza/uczniowie/delete/{id_analiza}', ['as' => 'analiza.uczniowie.delete', 'uses' =>'AnalizatorController@uczniowieDelete']);
Route::get('/analiza/wyniki/delete/{id_analiza}', ['as' => 'analiza.wyniki.delete', 'uses' =>'AnalizatorController@wynikiDelete']);
