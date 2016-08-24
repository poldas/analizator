<?php

namespace App\Http\Controllers;

use App\Logika\Analizator\AnalizaDanych;
use App\Wykres;
use Illuminate\Database\QueryException;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class WykresyController extends Controller
{
    public function wykresy($id_analiza)
    {
//        $analiza = new AnalizaDanych();
//        $dane = $analiza->pobierz();
        $daneWykresu = [];
        if (Session::has('daneWykresu')) {
            $daneWykresu = Session::get('daneWykresu');
        }
        return view('analiza.wykresy', compact('daneWykresu', 'id_analiza'));
    }

    public function parsujDaneWykresu()
    {
        $analiza = new AnalizaDanych();
        $dane = $analiza->pobierz();
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
