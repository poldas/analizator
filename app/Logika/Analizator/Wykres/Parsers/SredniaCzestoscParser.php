<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaCzestoscParser extends Parser {

    public function parseDataToChart($id_analiza)
    {
        $this->mapujCzestoscWynikowCalosc($id_analiza);
        $this->mapujCzestoscWynikowPlec($id_analiza);
        $this->mapujCzestoscWynikowLokalizacja($id_analiza);
        $this->mapujCzestoscWynikowDysleksja($id_analiza);
    }

    private function mapujCzestoscWynikowCalosc($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_CALOSC, $opcje);
        $this->mapuj_czestosc($dane_db);
    }

    private function mapujCzestoscWynikowPlec($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_PLEC, $opcje);
        $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_PLEC);
    }

    private function mapujCzestoscWynikowLokalizacja($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_LOKALIZACJA, $opcje);
        $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujCzestoscWynikowDysleksja($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_DYSLEKSJA, $opcje);
        $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_DYSLEKSJA);
    }

    protected function mapuj_czestosc($dane_db, $series_param_type = null)
    {
        $dataset = [];
        foreach ($dane_db as $row) {
            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $value = $row[self::COLUMN_NAME_ILOSC_WYNIKOW];
            $label = $row[self::COLUMN_NAME_SUMA];
            $wykres_klasa = $row[self::COLUMN_NAME_KLASA];
            if(!empty($series_param_type)) {
                $series_name = $row[$series_param_type];
                $series_type = $series_param_type;
            } else {
                $series_type = $series_name = 'bezpodzialu';
            }

            $chart_id = $this->getChartId($wykres_klasa, $series_type);
            $chart_name = $this->getChartName($wykres_klasa, $series_type);
            $dataset[$chart_id]['id'] = $chart_id;
            $dataset[$chart_id]['name'] = $chart_name;
            $dataset[$chart_id]['series'][$series_name][$label] = $value;
            $dataset[$chart_id]['labels'][$label] = $label;
            $dataset[$chart_id]['tags']['częstość wyników'] = 'częstość wyników';
            $dataset[$chart_id]['tags'][$wykres_klasa] = 'klasa '.$wykres_klasa;
            $dataset[$chart_id]['tags'][$this->translateSeriesType($series_type)] = $this->translateSeriesType($series_type);
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres_klasa, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        $klasa = $this->translateKlasa($wykres_klasa);
        return 'Częstość wyników, '.$klasa.' '.$series_name;
    }

    private function getOptions($id_analiza)
    {
        return [$id_analiza, $id_analiza];
    }

    protected function getChartId($wykres_klasa, $series_type)
    {
        return strtolower('czestoscklasa'.$wykres_klasa.$series_type);
    }
}