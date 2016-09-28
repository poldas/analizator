<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaCzestoscParser extends Parser implements IParseToChartData {

    public function getResult()
    {
        return $this->wynik;
    }

    public function parseDataToChart($id_analiza)
    {
        $this->mapujCzestoscWynikowCalosc($id_analiza);
        $this->mapujCzestoscWynikowPlec($id_analiza);
        $this->mapujCzestoscWynikowLokalizacja($id_analiza);
        $this->mapujCzestoscWynikowDysleksja($id_analiza);
    }

    private function mapujCzestoscWynikowCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, null, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::CZESTOSC_WYNIKOW_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }
}