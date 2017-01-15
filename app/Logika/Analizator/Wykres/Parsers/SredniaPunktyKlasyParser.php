<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaPunktyKlasyParser extends Parser {

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
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_CALOSC, $opcje);
        $this->mapuj_srednia_calosc($dane_db);
    }

    private function mapujSredniaDysleksja()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_DYSLEKSJA, $opcje);
        $this->mapuj_srednia_calosc($dane_db, Parser::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaLokalizacja()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_LOKALIZACJA, $opcje);
        $this->mapuj_srednia_calosc($dane_db, Parser::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaPlec()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_PLEC, $opcje);
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
            $this->prepareDataset($dataset, $row, $value, $label, null, null, $series_param_type);
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres1, $wykres2, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        return 'Średnia punktów -  '.$series_name;
    }

    private function getOptions()
    {
        return [$this->id_analiza, $this->id_analiza];
    }

    protected function getChartId($wykres1, $wykres2, $series_type)
    {
        return strtolower('sredniapunkty'.$series_type);
    }
}