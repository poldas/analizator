<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaZadaniaParser extends Parser implements IParseToChartData {

    private $wynik;

    public function getResult()
    {
        return $this->wynik;
    }
    
    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaZadaniaCalosc($id_analiza);
        $this->mapujSredniaZadaniaDysleksja($id_analiza);
        $this->mapujSredniaZadaniaLokalizacja($id_analiza);
        $this->mapujSredniaZadaniaPlec($id_analiza);
    }

    private function mapujSredniaZadaniaCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, null, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    protected function mapuj($dane_db, $column_srednia, $category_column_name, $series_name_kategorii = '', $series_name_wykresu = '', $nazwa = 'domyślne')
    {
        $seriesTable = [];
        $tags = [];
        foreach ($dane_db as $rowValue) {
            // przypisanie danych z bazy
            $rowValue = (array)$rowValue; // DB zwraca object, a potrzebny array
            $srednia = $rowValue[$column_srednia];
            $kategoria = $rowValue[$category_column_name]; // wartość z kolumny kategorii, np . 'A', 'szkola'
            $podzial = isset($rowValue[$series_name_kategorii])? $rowValue[$series_name_kategorii] : '';
            $series_name = $this->getSeriesName($podzial);

            $tags[$series_name] = $series_name;
            $tags[$kategoria] = $kategoria;

            // wartości serii wykresu
            $seriesTable[$series_name]['data'][$kategoria] = $srednia;
            $seriesTable[$series_name]['name'] = $series_name;
            $seriesTable[$series_name]['dataLabels'] = [
                'enabled' => true,
                'format' => '{point.y:.2f}'
            ];

        }
        $kategorie = array_keys($seriesTable[$series_name]['data']);
        $series = array_values($seriesTable);
        $chart = [];
        $chart['categories'] = $kategorie;
        $chart['series'] = $series;
        $chart['tags'] = $tags;
        $chart['name'] = 'Średnia klasy '.$this->getChartNameFromColumnName($series_name_kategorii);
        $this->addNewChart($chart);
    }
}