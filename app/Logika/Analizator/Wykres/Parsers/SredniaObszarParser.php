<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaObszarParser extends Parser implements IParseToChartData {

    public function parseDataToChart()
    {
        $this->mapujSredniaObszarCalosc();
        $this->mapujSredniaObszarDysleksja();
        $this->mapujSredniaObszarLokalizacja();
        $this->mapujSredniaObszarPlec();
    }

    private function mapujSredniaObszarCalosc()
    {
        $options = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_CALOSC, $options);
        $this->mapuj_zadania_calosc($dane_db);
    }

    private function mapujSredniaObszarDysleksja()
    {
        $options = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_DYSLEKSJA, $options);
        $this->mapuj_zadania_calosc($dane_db, Parser::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaObszarLokalizacja()
    {
        $options = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_LOKALIZACJA, $options);
        $this->mapuj_zadania_calosc($dane_db, Parser::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaObszarPlec()
    {
        $options = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_PLEC, $options);
        $this->mapuj_zadania_calosc($dane_db, Parser::COLUMN_NAME_PLEC);
    }

    protected function mapuj_zadania_calosc($dane_db, $series_param_type = null)
    {
        $dataset = [];
        foreach ($dane_db as $row) {
            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $value = $row[self::COLUMN_NAME_SREDNIA_PKT];
            $label = $row[self::COLUMN_NAME_OBSZAR];
            $wykres_klasa = $row[self::COLUMN_NAME_KLASA];
            $chart_id = $this->prepareDataset($dataset, $row, $value, $label, null, $wykres_klasa, $series_param_type);

            $dataset[$chart_id]['tags']['średnia obszary'] = 'średnia obszary';
            $dataset[$chart_id]['tags'][$wykres_klasa] = 'klasa '.$wykres_klasa;
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres1, $wykres2, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        $klasa = $this->translateKlasa($wykres2);
        return 'Średnia dla obszarów, '.$klasa.' '.$series_name;
    }

    private function getOptions()
    {
        return [$this->id_analiza, $this->id_analiza];
    }

    protected function getChartId($wykres1, $wykres2, $series_type)
    {
        return strtolower('obszaryklasa'.$wykres2.$series_type);
    }
}