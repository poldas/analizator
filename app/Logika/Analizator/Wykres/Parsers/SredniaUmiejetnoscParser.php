<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaUmiejetnoscParser extends Parser {

    public function parseDataToChart()
    {
        $this->mapujSredniaUmiejetnoscCalosc();
        $this->mapujSredniaUmiejetnoscDysleksja();
        $this->mapujSredniaUmiejetnoscLokalizacja();
        $this->mapujSredniaUmiejetnoscPlec();
    }

    private function mapujSredniaUmiejetnoscCalosc()
    {
        $options = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_CALOSC, $options);
        $this->mapuj_umiejetnosc_calosc($dane_db);
    }

    private function mapujSredniaUmiejetnoscDysleksja()
    {
        $options = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_DYSLEKSJA, $options);
        $this->mapuj_umiejetnosc_calosc($dane_db, self::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaUmiejetnoscLokalizacja()
    {
        $options = $this->getOptions();
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_LOKALIZACJA, $options);
        $this->mapuj_umiejetnosc_calosc($dane_db, self::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaUmiejetnoscPlec()
    {
        $options = $this->getOptions();
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
            $chart_id = $this->prepareDataset($dataset, $row, $value, $label, $wykres_obszar, $wykres_klasa, $series_param_type);
            $dataset[$chart_id]['tags'][$wykres_obszar] = 'obszar '.$wykres_obszar;
        }
        $this->addNewChart($dataset);
    }

    private function getOptions()
    {
        return [$this->id_analiza, $this->id_analiza, $this->id_analiza, $this->id_analiza];
    }

    protected function getChartId($wykres1, $wykres2, $series_type)
    {
        return strtolower('obszar'.$wykres1.'klasa'.$wykres2.$series_type);
    }

    protected function getChartName($wykres1, $wykres2, $series_type)
    {
        $klasa = $this->translateKlasa($wykres2);
        $series_name = $this->translateSeriesType($series_type);
        return 'Obszar '.$wykres1.', '.$klasa.', '.$series_name;
    }
}