<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaObszarParser extends Parser implements IParseToChartData {


    public function getResult()
    {
        return $this->wynik;
    }

    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaObszarCalosc($id_analiza);
        $this->mapujSredniaObszarDysleksja($id_analiza);
        $this->mapujSredniaObszarLokalizacja($id_analiza);
        $this->mapujSredniaObszarPlec($id_analiza);
    }

    private function mapujSredniaObszarCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_CALOSC, [$id_analiza, $id_analiza]);
        $this->mapuj_srednia_obszar($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_KLASA, Parser::COLUMN_NAME_OBSZAR, null, null, 'obszar całość');
    }

    private function mapujSredniaObszarDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $this->mapuj_srednia_obszar($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_DYSLEKSJA, Parser::COLUMN_NAME_KLASA, 'obszar dysleksja');
    }

    private function mapujSredniaObszarLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $this->mapuj_srednia_obszar($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_LOKALIZACJA, Parser::COLUMN_NAME_KLASA, 'obszar lokalizacja');
    }

    private function mapujSredniaObszarPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_PLEC, [$id_analiza, $id_analiza]);
        $this->mapuj_srednia_obszar($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_PLEC, Parser::COLUMN_NAME_KLASA, 'obszar plec');
    }

    protected function mapuj_srednia_obszar($dane_db, $column_srednia, $category_column_name, $series_name_kategorii = '', $series_name_wykresu = '', $nazwa = 'domyślne')
    {
        $seriesTable = [];
        $tags = [];
        dd($dane_db);
        foreach ($dane_db as $rowValue) {
//            var_dump($rowValue);
            // przypisanie danych z bazy
            $rowValue = (array)$rowValue; // DB zwraca object, a potrzebny array
            $srednia = $rowValue[$column_srednia];
            $kategoria = $rowValue[$category_column_name]; // wartość z kolumny kategorii, np . 'A', 'szkola'
            $podzial = isset($rowValue[$series_name_kategorii])? $rowValue[$series_name_kategorii] : '';
            $series_name = $this->getSeriesName($podzial);

            $tags[$series_name] = $series_name;
            $tags[$kategoria] = $kategoria;

            // wartości serii wykresu
            $seriesTable[$series_name]['data'][] = $srednia;
            $seriesTable[$series_name]['name'] = $series_name;
            $seriesTable[$series_name]['dataLabels'] = [
                'enabled' => true,
                'format' => '{point.y:.2f}'
            ];
        }
//        var_dump($seriesTable);
        $kategorie = array_keys($tags);
        $series = array_values($seriesTable);
        $chart = [];
        $chart['categories'] = $kategorie;
        $chart['series'] = $series;
        $chart['tags'] = $tags;
        $chart['name'] = 'Średnia obszarów'.$this->getChartNameFromColumnName($series_name_kategorii);
//        var_dump($chart);
        $this->addNewChart($chart);
    }
}