<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\Wykres\Parsers\Parser;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaKlasyParser extends Parser {

    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaCalosc($id_analiza);
        $this->mapujSredniaDysleksja($id_analiza);
        $this->mapujSredniaLokalizacja($id_analiza);
        $this->mapujSredniaPlec($id_analiza);
    }

    private function mapujSredniaCalosc($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA, $opcje);
        $this->mapuj_srednia_calosc($dane_db);
    }

    private function mapujSredniaDysleksja($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_DYSLEKSJA, $opcje);
        $this->mapuj_srednia_calosc($dane_db, Parser::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaLokalizacja($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_LOKALIZACJA, $opcje);
        $this->mapuj_srednia_calosc($dane_db, Parser::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaPlec($id_analiza)
    {
        $opcje = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_PLEC, $opcje);
        $this->mapuj_srednia_calosc($dane_db, Parser::COLUMN_NAME_PLEC);

    }

    protected function mapuj_srednia_calosc($dane_db, $series_param_type = null)
    {
        $dataset = [];
        foreach ($dane_db as $row) {
            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $value = $row[self::COLUMN_NAME_SREDNIA_PKT];
            $label = $row[self::COLUMN_NAME_KLASA];
            if(!empty($series_param_type)) {
                $series_name = $row[$series_param_type];
                $series_type = $series_param_type;
            } else {
                $series_type = $series_name = 'bezpodzialu';
            }

            $chart_id = $this->getChartId($series_type);
            $chart_name = $this->getChartName($series_type);
            $dataset[$chart_id]['id'] = $chart_id;
            $dataset[$chart_id]['name'] = $chart_name;
            $dataset[$chart_id]['series'][$series_name][$label] = $value;
            $dataset[$chart_id]['labels'][$label] = $label;
            $dataset[$chart_id]['tags'][$this->translateSeriesType($series_type)] = $this->translateSeriesType($series_type);
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        return 'Średnia '.$series_name;
    }

    private function getOptions($id_analiza)
    {
        return [$id_analiza, $id_analiza];
    }

    protected function getChartId($series_type)
    {
        return strtolower('srednia'.$series_type);
    }
}