<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaKlasyParser extends Parser {

    public function parseDataToChart()
    {
        $this->mapujSredniaCalosc();
        $this->mapujSredniaDysleksja();
        $this->mapujSredniaLokalizacja();
        $this->mapujSredniaPlec();
    }

    private function mapujSredniaCalosc()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA, $opcje);
        $this->mapuj_srednia_calosc($dane_db);
    }

    private function mapujSredniaDysleksja()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_DYSLEKSJA, $opcje);
        $this->mapuj_srednia_calosc($dane_db, Parser::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaLokalizacja()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_LOKALIZACJA, $opcje);
        $this->mapuj_srednia_calosc($dane_db, Parser::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaPlec()
    {
        $opcje = $this->getOptions();
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
            $chart_id = $this->prepareDataset($dataset, $row, $value, $label, null, null, $series_param_type);
            $dataset[$chart_id]['tags']['średnia procenty'] = 'średnia procenty';
            $this->prepareSeries($dataset, $chart_id);
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres1, $wykres2, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        return 'Średnia '.$series_name;
    }

    private function getOptions()
    {
        return [$this->id_analiza, $this->id_analiza];
    }

    protected function getChartId($wykres1, $wykres2, $series_type)
    {
        return strtolower('srednia'.$series_type);
    }
}