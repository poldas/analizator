<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaUmiejetnoscParser extends Parser implements IParseToChartData {


    public function getResult()
    {
        return $this->wynik;
    }

    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaUmiejetnoscCalosc($id_analiza);
        $this->mapujSredniaUmiejetnoscDysleksja($id_analiza);
        $this->mapujSredniaUmiejetnoscLokalizacja($id_analiza);
        $this->mapujSredniaUmiejetnoscPlec($id_analiza);
    }

    private function mapujSredniaUmiejetnoscCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_CALOSC, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $this->mapuj_umiejetnosc_calosc($dane_db);
    }

    private function mapujSredniaUmiejetnoscDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_DYSLEKSJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $this->mapuj_umiejetnosc_podzial($dane_db, self::COLUMN_NAME_DYSLEKSJA);
    }

    private function mapujSredniaUmiejetnoscLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_LOKALIZACJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $this->mapuj_umiejetnosc_podzial($dane_db, self::COLUMN_NAME_LOKALIZACJA);
    }

    private function mapujSredniaUmiejetnoscPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_PLEC, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $this->mapuj_umiejetnosc_podzial($dane_db, self::COLUMN_NAME_PLEC);
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
     * @param $dane_db
     */
    protected function mapuj_umiejetnosc_calosc($dane_db)
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {
            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $value = $row[self::COLUMN_NAME_SREDNIA_PKT];
            $label = $row[self::COLUMN_NAME_UMIEJETNOSC];
            $series_name = 'bezpodzialu';
            $wykres_obszar = $row[self::COLUMN_NAME_OBSZAR];
            $wykres_klasa = $row[self::COLUMN_NAME_KLASA];

            $kategorie[$wykres_obszar][$label] = $label;

            $nazwa_wykresu = $this->getChartName($wykres_obszar, $wykres_klasa, $series_name);
            $dataset[$nazwa_wykresu]['series'][$series_name][$label] = $value;
            $dataset[$nazwa_wykresu]['labels'][$label] = $label;
        }
        return $dataset;
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
     * @param $dane_db
     */
    protected function mapuj_umiejetnosc_podzial($dane_db, $series_type)
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {
            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $value = $row[self::COLUMN_NAME_SREDNIA_PKT];
            $label = $row[self::COLUMN_NAME_UMIEJETNOSC];
            $series_name = $row[$series_type];
            $wykres_obszar = $row[self::COLUMN_NAME_OBSZAR];
            $wykres_klasa = $row[self::COLUMN_NAME_KLASA];

            $kategorie[$wykres_obszar][$label] = $label;

            $nazwa_wykresu = $this->getChartName($wykres_obszar, $wykres_klasa, $series_type);
            $dataset[$nazwa_wykresu]['series'][$series_name][$label] = $value;
            $dataset[$nazwa_wykresu]['labels'][$label] = $label;
            $dataset[$nazwa_wykresu]['tags'][$label] = $label;
            $dataset[$nazwa_wykresu]['tags'][$series_name] = $series_name;
            $dataset[$nazwa_wykresu]['tags'][$series_type] = $series_type;
            $dataset[$nazwa_wykresu]['tags'][$wykres_obszar] = $wykres_obszar;
            $dataset[$nazwa_wykresu]['tags'][$wykres_klasa] = $wykres_klasa;
        }
        dd($dataset);
        return $dataset;
    }
}