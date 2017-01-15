<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaObszarParser extends Parser implements IParseToChartData {


    public function getResult()
    {
        return $this->wynik;
    }

    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaObszarCalosc($id_analiza);
        $this->mapujSredniaObszarDysleksja($id_analiza);
        $this->mapujSredniaObszarLokalizacja($id_analiza);
        $this->mapujSredniaObszarPlec($id_analiza);
    }

    private function mapujSredniaObszarCalosc($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_CALOSC, $options);
        $this->mapuj_zadania_calosc($dane_db);
    }

    private function mapujSredniaObszarDysleksja($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_DYSLEKSJA, $options);
        $this->mapuj_zadania_calosc($dane_db, Parser::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaObszarLokalizacja($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_LOKALIZACJA, $options);
        $this->mapuj_zadania_calosc($dane_db, Parser::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaObszarPlec($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
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
            $dataset[$chart_id]['tags']['średnia obszary'] = 'średnia obszary';
            $dataset[$chart_id]['tags'][$wykres_klasa] = 'klasa '.$wykres_klasa;
            $dataset[$chart_id]['tags'][$this->translateSeriesType($series_type)] = $this->translateSeriesType($series_type);
        }
        $this->addNewChart($dataset);
    }

    protected function getChartName($wykres_klasa, $series_type)
    {
        $series_name = $this->translateSeriesType($series_type);
        $klasa = $this->translateKlasa($wykres_klasa);
        return 'Średnia dla obszarów, '.$klasa.' '.$series_name;
    }

    private function getOptions($id_analiza)
    {
        return [$id_analiza, $id_analiza];
    }

    protected function getChartId($wykres_klasa, $series_type)
    {
        return strtolower('obszaryklasa'.$wykres_klasa.$series_type);
    }

}