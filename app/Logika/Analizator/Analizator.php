<?php
namespace App\Logika\Analizator;
use App\Analiza;
use App\Logika\Analizator\AnalizaDanych;
use App\Obszar;
use App\ParseAnalizaFileData;
use App\Uczen;
use App\Wynik;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class Analizator {

    public function konfiguruj($id)
    {
        $this->addTest($id);
    }

    public function getAll()
    {
        $analizy = Analiza::all();
        return $analizy;
    }

    public function createDataSet($request)
    {
        $request['file_path'] = $request->file('file')->getClientOriginalName();
        $analiza = $this->addNewAnaliza($request);
        $this->addFile($request, $analiza);
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

    public function addTest($newTestData)
    {
        $analiza = Analiza::find($newTestData);
        $this->addWyniki($analiza );
    }

    public function parseData($data)
    {
        $this->parsujDaneWykres($data);
    }

    private function parsujDaneWykres($id)
    {
        $analiza = new AnalizaDanych();
        $dane = $analiza->pobierz();
        var_dump($dane);
    }
    public function getTest($testId)
    {
        $analiza = Analiza::find($testId);
        if (!$analiza) {
            return redirect('analiza/lista');
        }
        $uczniowie = Uczen::where(['id_analiza' => $testId])->get();
        return [$analiza, $uczniowie];
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
        } catch(QueryException$e) {
            request()->session()->put('alert-danger', 'Błąd w zapisie danych uczniów.');
        }
    }

    private function addFile($request,Analiza $analiza) {
        $isOk = Storage::disk('local')->put($analiza->file_path,
            file_get_contents($request->file('file')->getRealPath())
        );

        return $isOk;
    }

    private function addNewAnaliza($request)
    {
        $analiza = Analiza::create($request->all());
        $analiza->file_path = 'analiza_csv/'.$analiza->id.'-'.preg_replace("/\s/", '_', $analiza->file_path);
        $analiza->save();

        return $analiza;
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
}