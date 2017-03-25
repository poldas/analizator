<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaZadaniaParser extends Parser {

    public function parseDataToChart()
    {
        $this->mapujSredniaZadaniaCalosc();
        $this->mapujSredniaZadaniaDysleksja();
        $this->mapujSredniaZadaniaLokalizacja();
        $this->mapujSredniaZadaniaPlec();
    }

    private function mapujSredniaZadaniaCalosc()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_CALOSC, $opcje);
        $this->mapuj_zadania_calosc($dane_db);
    }

    private function mapujSredniaZadaniaDysleksja()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_DYSLEKSJA, $opcje);
        $this->mapuj_zadania_calosc($dane_db, self::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaZadaniaLokalizacja()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_LOKALIZACJA, $opcje);
        $this->mapuj_zadania_calosc($dane_db, self::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaZadaniaPlec()
    {
        $opcje = $this->getOptions();
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
            $chart_id = $this->prepareDataset($dataset, $row, $value, $label, null, $wykres_klasa, $series_param_type);
            $dataset[$chart_id]['tags']['średnia zadań'] = 'średnia zadań';
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres1, $wykres2, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        $klasa = $this->translateKlasa($wykres2);
        return 'Średnia zadań, '.$klasa.' '.$series_name;
    }

    private function getOptions()
    {
        return [$this->id_analiza, $this->id_analiza];
    }

    protected function getChartId($wykres1, $wykres2, $series_type)
    {
        return strtolower('zadaniaklasa'.$wykres2.$series_type);
    }
}