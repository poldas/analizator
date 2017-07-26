<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaCzestoscParser extends Parser {

    public function parseDataToChart()
    {
        $this->mapujCzestoscWynikowCalosc();
        $this->mapujCzestoscWynikowPlec();
        $this->mapujCzestoscWynikowLokalizacja();
        $this->mapujCzestoscWynikowDysleksja();
    }

    private function mapujCzestoscWynikowCalosc()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_CALOSC, $opcje);
        $this->mapuj_czestosc($dane_db);
    }

    private function mapujCzestoscWynikowPlec()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_PLEC, $opcje);
        $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_PLEC);
    }

    private function mapujCzestoscWynikowLokalizacja()
    {
        $opcje = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_LOKALIZACJA, $opcje);
        $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujCzestoscWynikowDysleksja()
    {
        $opcje = $this->getOptions();
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
            $chart_id = $this->prepareDataset($dataset, $row, $value, $label, null, $wykres_klasa, $series_param_type);
            $dataset[$chart_id]['tags']['częstość wyników'] = 'częstość wyników';
            $this->prepareSeries($dataset, $chart_id);
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres1, $wykres2, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        $klasa = $this->translateKlasa($wykres2);
        return 'Częstość wyników, '.$klasa.' '.$series_name;
    }

    private function getOptions()
    {
        return [$this->id_analiza, $this->id_analiza];
    }

    protected function getChartId($wykres1, $wykres2, $series_type)
    {
        return strtolower('czestoscklasa'.$wykres2.$series_type);
    }
}