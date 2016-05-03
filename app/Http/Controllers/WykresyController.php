<?php

namespace App\Http\Controllers;

use App\Logika\AnalizaDanych;
use App\Wykres;
use Illuminate\Database\QueryException;

use App\Http\Requests;

class WykresyController extends Controller
{
    public function wykresy()
    {
        $analiza = new AnalizaDanych();
        $dane = $analiza->pobierz();
        return view('analiza.wykresy', compact('dane'));
    }

    public function parsujDaneWykresu()
    {
        $analiza = new AnalizaDanych();
        $dane = $analiza->pobierz();
        var_dump($dane);
    }

    protected function getWykresy()
    {
        $wykresy = [];
        try {
            $wykresy = Wykres::all();
        } catch(QueryException $e) {
            request()->session()->put('alert-danger', 'Błąd w zapisie danych wykresów.');
        }
        return $wykresy;
    }
}
