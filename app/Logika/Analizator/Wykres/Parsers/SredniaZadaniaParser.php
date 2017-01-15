<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaZadaniaParser extends Parser {

    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaZadaniaCalosc($id_analiza);
        $this->mapujSredniaZadaniaDysleksja($id_analiza);
        $this->mapujSredniaZadaniaLokalizacja($id_analiza);
        $this->mapujSredniaZadaniaPlec($id_analiza);
    }

    private function mapujSredniaZadaniaCalosc($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_CALOSC, $opcje);
        $this->mapuj_zadania_calosc($dane_db);
    }

    private function mapujSredniaZadaniaDysleksja($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_DYSLEKSJA, $opcje);
        $this->mapuj_zadania_calosc($dane_db, self::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaZadaniaLokalizacja($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_LOKALIZACJA, $opcje);
        $this->mapuj_zadania_calosc($dane_db, self::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaZadaniaPlec($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_PLEC, $opcje);
        $this->mapuj_zadania_calosc($dane_db, self::COLUMN_NAME_PLEC);
    }

    protected function mapuj_zadania_calosc($dane_db, $series_param_type = null)
    {
        $dataset = [];
        foreach ($dane_db as $row) {
            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $value = $row[self::COLUMN_NAME_SREDNIA_PKT];
            $label = $row[self::COLUMN_NAME_NR_ZADANIA];
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
            $dataset[$chart_id]['tags']['średnia zadań'] = 'średnia zadań';
            $dataset[$chart_id]['tags'][$wykres_klasa] = 'klasa '.$wykres_klasa;
            $dataset[$chart_id]['tags'][$this->translateSeriesType($series_type)] = $this->translateSeriesType($series_type);
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres_klasa, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        $klasa = $this->translateKlasa($wykres_klasa);
        return 'Średnia zadań, '.$klasa.' '.$series_name;
    }

    private function getOptions($id_analiza)
    {
        return [$id_analiza, $id_analiza];
    }

    protected function getChartId($wykres_klasa, $series_type)
    {
        return strtolower('zadaniaklasa'.$wykres_klasa.$series_type);
    }
}