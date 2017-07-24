<?php
namespace App\Http\Controllers;
use App\Logika\Analizator\AnalizaDanych;
use App\Logika\Analizator\Analizator;
use App\Wykres;
use Illuminate\Database\QueryException;
use App\Http\Requests;

class WykresyController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
        $this->analizator = new Analizator();
    }

    public function save($id_wykres)
    {
        $dane = request()->all();
        $wykres = Wykres::find($id_wykres);
        $wykres->update($dane);
        return response()->json($wykres);
    }
    public function wykresy($id_analiza)
    {
        $wykresy = Wykres::where(['id_analiza' => $id_analiza])->get();
        return view('analiza.wykresy', compact('wykresy', 'id_analiza'));
    }

    public function wykresyapi($id_analiza)
    {
        $wykresy = Wykres::where([
            ['id_analiza', '=', $id_analiza]//, ['tags', 'LIKE', '%wszystko%dysleksja']
        ])->get();
        return response()->json($wykresy);
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
