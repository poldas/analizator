<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaUmiejetnoscParser extends Parser {


    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaUmiejetnoscCalosc($id_analiza);
        $this->mapujSredniaUmiejetnoscDysleksja($id_analiza);
        $this->mapujSredniaUmiejetnoscLokalizacja($id_analiza);
        $this->mapujSredniaUmiejetnoscPlec($id_analiza);
    }

    private function mapujSredniaUmiejetnoscCalosc($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_CALOSC, $options);
        $this->mapuj_umiejetnosc_calosc($dane_db);
    }

    private function mapujSredniaUmiejetnoscDysleksja($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_DYSLEKSJA, $options);
        $this->mapuj_umiejetnosc_calosc($dane_db, self::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaUmiejetnoscLokalizacja($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_LOKALIZACJA, $options);
        $this->mapuj_umiejetnosc_calosc($dane_db, self::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaUmiejetnoscPlec($id_analiza)
    {
        $options = $this->getOptions($id_analiza);
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_PLEC, $options);
        $this->mapuj_umiejetnosc_calosc($dane_db, self::COLUMN_NAME_PLEC);
    }

    /**
     *  <nazwa wykresu>: np. 'obszari'.'klasaa'.<series name>
     *  <nazwa wykresu>: np. 'obszariv'.'szkola'.'plec'
     *  <series name>: np. 'plec', 'lokalizacja', 'bezpodzialu', 'dysleksja'
     *          zawiera wartości do wyświetlenia dla danego typu wykresu i nazwy serii np. dla chlopców obszar 1 klasa A
     *  <label name>: np. nazwy dla których prezentowane są wartości z series, np. klasy, umiejętności, a,b,c,d,e
     *
     *  $dataset[<nazwa wykresu>]['series'][<series name>][]: wartości do wyświetlenia np. dla chlopców obszar 1 klasa A
     *  $dataset[<nazwa wykresu>]['labels'][<label name>]: nazwy dla których prezentowane są wartości z series, np. klasy, umiejętności
     *
     * @param array $dane_db dane z bazy danych
     * @param string $series_type typ serii, np dysleksja, bez podzialu, plec, lokalizacja
     */
    protected function mapuj_umiejetnosc_calosc($dane_db, $series_param_type = null)
    {
        $dataset = [];
        foreach ($dane_db as $row) {
            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $value = $row[self::COLUMN_NAME_SREDNIA_PKT];
            $label = $row[self::COLUMN_NAME_UMIEJETNOSC];
            $wykres_obszar = $row[self::COLUMN_NAME_OBSZAR];
            $wykres_klasa = $row[self::COLUMN_NAME_KLASA];
            if(!empty($series_param_type)) {
                $series_name = $row[$series_param_type];
                $series_type = $series_param_type;
            } else {
                $series_type = $series_name = 'bezpodzialu';
            }

            $chart_id = $this->getChartId($wykres_obszar, $wykres_klasa, $series_type);
            $chart_name = $this->getChartName($wykres_obszar, $wykres_klasa, $series_type);
            $dataset[$chart_id]['id'] = $chart_id;
            $dataset[$chart_id]['name'] = $chart_name;
            $dataset[$chart_id]['series'][$series_name][$label] = $value;
            $dataset[$chart_id]['labels'][$label] = $label;
            $dataset[$chart_id]['tags'][$this->translateSeriesType($series_type)] = $this->translateSeriesType($series_type);
            $dataset[$chart_id]['tags'][$wykres_obszar] = 'obszar '.$wykres_obszar;
            $dataset[$chart_id]['tags'][$wykres_klasa] = 'klasa '.$wykres_klasa;
        }
        $this->addNewChart($dataset);
    }

    private function getOptions($id_analiza)
    {
        return [$id_analiza, $id_analiza, $id_analiza, $id_analiza];
    }

    protected function getChartId($wykres_obszar, $wykres_klasa, $series_type)
    {
        return strtolower('obszar'.$wykres_obszar.'klasa'.$wykres_klasa.$series_type);
    }

    protected function getChartName($wykres_obszar, $wykres_klasa, $series_type)
    {
        $klasa = $this->translateKlasa($wykres_klasa);
        $series_name = $this->translateSeriesType($series_type);
        return 'Obszar '.$wykres_obszar.', '.$klasa.', '.$series_name;
    }
}