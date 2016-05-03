<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Logika\AnalizaDanych;
use App\Obszar;
use App\Wynik;
use App\Analiza;
use App\Uczen;
use App\ParseAnalizaFileData;
use Storage;
use Illuminate\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\File;
use Illuminate\Database\QueryException;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class AnalizatorController extends Controller
{

    public function lista()
    {
        $analizy = Analiza::all();
        return view('analiza.lista', compact('analizy'));
    }

    public function show($id)
    {
        $analiza = Analiza::find($id);
        if (!$analiza) {
            return redirect('analiza/lista');
        }
        $uczniowie = Uczen::where(['id_analiza' => $id])->get();
        return view('analiza.show', compact('analiza', 'uczniowie'));
    }

    public function konfiguruj($id)
    {
        $analiza = Analiza::find($id);
        $this->addWyniki($analiza);
        return redirect('analiza/show/'.$id);
    }

    public function parsuj($id)
    {
        $analiza = Analiza::find($id);
        $this->parsujDaneWykres($analiza);
        return redirect('analiza/show/'.$id);
    }

    public function delete($id)
    {
        $analiza = Analiza::where('id', $id)->first();
        $i = Storage::disk('local')->delete($analiza->file_path)
            && $analiza->delete();
        if ($i) {
            $type = 'alert-success';
            $message = "Udało się usunąć ".$analiza->file_path;
        } else {
            $type = 'alert-danger';
            $message = "Nie udało się usunąć ".$analiza->file_path;
        }
        request()->session()->put($type, $message);
        return redirect('analiza/lista');
    }

    public function obszarDelete($id_analiza)
    {
        $i = Obszar::where('id_analiza', $id_analiza)->delete();
        if ($i) {
            $type = 'alert-success';
            $message = "Udało się usunąć obszary dla analizy o id: ".$id_analiza;
        } else {
            $type = 'alert-danger';
            $message = "Nie udało się usunąć obszary dla analizy o id: ".$id_analiza;
        }
        request()->session()->put($type, $message);
        return redirect('analiza/show/'.$id_analiza);
    }

    public function wynikiDelete($id_analiza)
    {
        $i = Wynik::where('id_analiza', $id_analiza)->delete();
        if ($i) {
            $type = 'alert-success';
            $message = "Udało się usunąć wyniki dla analizy o id: ".$id_analiza;
        } else {
            $type = 'alert-danger';
            $message = "Nie udało się usunąć wyniki dla analizy o id: ".$id_analiza;
        }
        request()->session()->put($type, $message);
        return redirect('analiza/show/'.$id_analiza);
    }

    public function uczniowieDelete($id_analiza)
    {
        $i = Uczen::where('id_analiza', $id_analiza)->delete();
        if ($i) {
            $type = 'alert-success';
            $message = "Udało się usunąć uczniów dla analizy o id: ".$id_analiza;
        } else {
            $type = 'alert-danger';
            $message = "Nie udało się usunąć uczniów dla analizy o id: ".$id_analiza;
        }
        request()->session()->put($type, $message);
        return redirect('analiza/show/'.$id_analiza);
    }

    private function addFile($request,Analiza $analiza) {
        $isOk = Storage::disk('local')->put($analiza->file_path,
            file_get_contents($request->file('file')->getRealPath())
        );

        return $isOk;
    }


    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function createForm()
    {
        return view('analiza.create');
    }

    public function createNew()
    {
        $request = request();
        try{
            $this->validate($request, [
                'nazwa' => 'required|max:150',
                'file' => 'required',
            ]);
        } catch(ValidationException $e) {
            return redirect('analiza/create')
                ->withErrors($e->getMessage())
                ->withInput();
        }
        $request['file_path'] = $request->file('file')->getClientOriginalName();
        $analiza = $this->addNewAnaliza($request);
        $this->addFile($request, $analiza);
        request()->session()->put('alert-success', 'Dane zapisane');
        return  redirect('analiza/lista');
    }

    private function addNewAnaliza($request)
    {
        $analiza = Analiza::create($request->all());
        $analiza->file_path = 'analiza_csv/'.$analiza->id.'-'.preg_replace("/\s/", '_', $analiza->file_path);
        $analiza->save();

        return $analiza;
    }

    private function addWyniki(Analiza $analiza)
    {
        $dataSource = $this->getDataSource($analiza);
        $parser = $this->parseDataSource($dataSource, $analiza->id);
        $this->addDataToDB($parser);
    }

    private function getDataSource(Analiza $analiza)
    {
        try {
            $csvStringData = Storage::get($analiza->file_path);
            $csvData = explode("\n", $csvStringData);
            $csvData = array_map('str_getcsv', $csvData);
        } catch (Illuminate\Filesystem\FileNotFoundException $exception) {
            die("The file doesn't exist");
        }

        return $csvData;
    }

    private function parseDataSource($dataSource, $analiza_id)
    {
        $parser = new ParseAnalizaFileData(['analiza_id' => $analiza_id]);
        $parser->parsuj_dane($dataSource);
        $parser->generuj_wyniki_egzaminu();
        $parser->generuj_obszary();
        return $parser;
    }

    private function addDataToDB(ParseAnalizaFileData $parser)
    {
        try {
            Obszar::insert($parser->dane_obszar);
            request()->session()->put('alert-success', 'Obszary zapisane.');
        } catch(QueryException $e) {
            request()->session()->put('alert-danger', 'Błąd w zapisie danych obszaru.');
        }
        try {
            Wynik::insert($parser->dane_wyniki_egzaminu);
            request()->session()->put('alert-success', 'Wyniki zapisane.');
        } catch(QueryException $e) {
            request()->session()->put('alert-danger', 'Błąd w zapisie danych wyników.');
        }
        try {
            Uczen::insert($parser->dane_uczniowie);
            request()->session()->put('alert-success', 'Uczniowie zapisani.');
        } catch(QueryException $e) {
            request()->session()->put('alert-danger', 'Błąd w zapisie danych uczniów.');
        }
    }

    private function parsujDaneWykres(Analiza $analiza)
    {
        $analiza = new AnalizaDanych();
        $dane = $analiza->pobierz();
//        var_dump($dane);
    }
}