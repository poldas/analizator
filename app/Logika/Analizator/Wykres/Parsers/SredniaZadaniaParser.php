<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaZadaniaParser extends Parser implements IParseToChartData {

    private $wynik;

    public function getResult()
    {
        return $this->wynik;
    }
    
    public function parseDataToChart($id_analiza)
    {
        $this->mapujSredniaZadaniaCalosc($id_analiza);
        $this->mapujSredniaZadaniaDysleksja($id_analiza);
        $this->mapujSredniaZadaniaLokalizacja($id_analiza);
        $this->mapujSredniaZadaniaPlec($id_analiza);
    }

    private function mapujSredniaZadaniaCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, null, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_ZADANIA_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }
}