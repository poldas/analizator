<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaCzestoscParser extends Parser implements IParseToChartData {

    public function getResult()
    {
        return $this->wynik;
    }

    public function parseDataToChart($id_analiza)
    {
        $this->mapujCzestoscWynikowCalosc($id_analiza);
        $this->mapujCzestoscWynikowPlec($id_analiza);
        $this->mapujCzestoscWynikowLokalizacja($id_analiza);
        $this->mapujCzestoscWynikowDysleksja($id_analiza);
    }

    private function mapujCzestoscWynikowCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, null, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    protected function mapuj_czestosc($dane_db, $column_srednia, $category_column_name, $series_name_kategorii = '', $podzial_wykresu = '', $name)
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {

            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $srednia = $row[$column_srednia];
            $kategoria = $row[$category_column_name];
            $podzial = $series_name_kategorii ? $row[$series_name_kategorii] : 'wszystko';
            $wykres_podzial = $podzial_wykresu ? $row[$podzial_wykresu] : '';

            $kategorie[$wykres_podzial][$kategoria] = $kategoria;

            $dataset[$wykres_podzial][$podzial][] = $srednia;
        }
        $chartsTable = [];
        foreach ($dataset as $chartType => $chartSeriesData) {
//            var_dump(array_keys($kategorie[$chartType]));
//            var_dump($chartType);
            $chart = [];
            $podzial = isset($rowValue[$series_name_kategorii])? $rowValue[$series_name_kategorii] : '';
            $series_name = $this->getSeriesName($podzial);
            $chart['data'] = array_values($chartSeriesData);
            $chart['name'] = $series_name;
            $chart['dataLabels'] = [
                'enabled' => true,
                'format' => '{point.y:.2f}'
            ];

            $chartsTable[] = $chart;
        }
    }
}