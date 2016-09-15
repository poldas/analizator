<?php
namespace App\Logika\Analizator;

use App\Analiza;
use App\Obszar;
use App\Uczen;
use App\Wynik;
use App\Logika\Analizator\ParseDataSource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class Analizator {

    public function konfiguruj($id)
    {
        $analiza = Analiza::find($id);
        $dataSource = $this->getDataSourceFromFile($analiza->file_path);
        $parser = new ParseDataSource();
        $parser->setAnalizaId($id)
            ->setDataToParse($dataSource);
        $parser->parse();

        $this->addDataToDB($parser);
    }

    public function getAllAnalize()
    {
        $analizy = Analiza::all();
        return $analizy;
    }

    public function createDataSetFromRequest($request)
    {
        $storage = new SaveDataSource();
        $file = $request->file('file');
        $request['file_path'] = $file->getClientOriginalName();
        $storage->save($request->all(), $file);
        request()->session()->put('alert-success', 'Dane zapisane');
    }

    public function deleteDataSet($id)
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
    }


    public function parseData($id)
    {
        $analizator = new AnalizaHelpers();
        $analizator->addChartsData($id, AnalizaHelpers::TYP_SREDNIA);
//            ->addChartsData($id, AnalizaHelpers::TYP_SREDNIA_PKT);

        $dane = $analizator->getChartsData($id);
        return $dane;
    }

    public function getAnalizeById($testId)
    {
        $analiza = Analiza::find($testId);
        if (!$analiza) {
            return ['',''];
        }
        $uczniowie = Uczen::where(['id_analiza' => $testId])
            ->orderBy('nr_ucznia', 'ASC')->get();
        return [$analiza, $uczniowie];
    }


    private function getDataSourceFromFile($file_path)
    {
        try {
            $csvStringData = Storage::get($file_path);
            $csvData = explode("\n", $csvStringData);
            $csvData = array_map('str_getcsv', $csvData);
        } catch (Illuminate\Filesystem\FileNotFoundException $exception) {
            die("The file doesn't exist");
        }

        return $csvData;
    }

    private function addDataToDB(ParseDataSource $parser)
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
        } catch(QueryException$e) {
            request()->session()->put('alert-danger', 'Błąd w zapisie danych uczniów.');
        }
    }

    public function obszarDelete($id_analiza)
    {
        $i = Obszar::where('id_analiza', $id_analiza)->delete();
        if ($i) {
            $type = 'alert-success';
            $message = "Obszary dla analizy o id: ".$id_analiza;
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
            $message = "Wyniki dla analizy o id: ".$id_analiza;
        } else {
            $type = 'alert-danger';
            $message = "Nie udało się usunąć wyniki dla analizy o id: ".$id_analiza;
        }
        request()->session()->put($type, $message);
    }

    public function uczniowieDelete($id_analiza)
    {
        $i = Uczen::where('id_analiza', $id_analiza)->delete();
        if ($i) {
            $type = 'alert-success';
            $message = "Uczniowie uczniów dla analizy o id: ".$id_analiza;
        } else {
            $type = 'alert-danger';
            $message = "Nie udało się usunąć uczniów dla analizy o id: ".$id_analiza;
        }
        request()->session()->put($type, $message);
        return redirect('analiza/show/'.$id_analiza);
    }
}