<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\Wykres\Parsers\Parser;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaKlasyParser extends Parser implements IParseToChartData {

    public function getResult()
    {
        return $this->wynik;
    }

    public function parseDataToChart($id_analiza)
    {

        $this->mapujSredniaCalosc($id_analiza);

        $this->mapujSredniaDysleksja($id_analiza);

        $this->mapujSredniaLokalizacja($id_analiza);

        $this->mapujSredniaPlec($id_analiza);
    }

    private function map()
    {
        
    }
    private function mapujSredniaCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db);
    }

    private function mapujSredniaDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db, Parser::COLUMN_NAME_DYSLEKSJA, null, 'Średnia');
    }

    private function mapujSredniaLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db, Parser::COLUMN_NAME_LOKALIZACJA, null, 'Średnia');
    }

    private function mapujSredniaPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_PLEC, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db, Parser::COLUMN_NAME_PLEC, null, 'Średnia');
    }

    protected function mapuj_dla_sredniej_klasa($dane_db, $series_name_kategorii = '')
    {
        $seriesTable = [];
        $tags = [];
        $categories = [];
        foreach ($dane_db as $rowValue) {
            // przypisanie danych z bazy
            $rowValue = (array)$rowValue; // DB zwraca object, a potrzebny array
            $srednia = $rowValue[self::COLUMN_NAME_SREDNIA_PKT];
            $klasa = $rowValue[self::COLUMN_NAME_KLASA]; // wartość z kolumny kategorii, np . 'A', 'szkola'
            $podzial = isset($rowValue[$series_name_kategorii])? $rowValue[$series_name_kategorii] : '';
            $series_name = $this->getSeriesName($podzial);
            $categories[$klasa] = 'Klasa '.$klasa;
            $tags[$series_name] = $series_name;
            $tags[$klasa] = $klasa;

            // wartości serii wykresu
            $seriesTable[$series_name]['data'][] = $srednia;
            $seriesTable[$series_name]['name'] = $series_name;

        }
        $kategorie = array_values($categories);
        $series = array_values($seriesTable);
        $chart = [];
        $chart['categories'] = $kategorie;
        $chart['series'] = array_values($series);
        $chart['tags'] = $tags;
        $chart['name'] = 'Średnia klasy '.$this->getChartNameFromColumnName($series_name_kategorii);
        $chart['id'] = sha1($chart['name']);
        $this->addNewChart($chart);
    }
}