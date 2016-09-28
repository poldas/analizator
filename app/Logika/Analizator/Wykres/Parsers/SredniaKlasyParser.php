<?php
namespace App\Logika\Analizator\Wykres\Parsers;
use App\Logika\Analizator\Wykres\Parsers\Parser;
use App\Logika\Analizator\ZapytaniaSql;

class SredniaKlasyParser extends Parser implements IParseToChartData {

    public function getResult()
    {
        return $this->wynik;
    }

    public function parseDataToChart($id_analiza)
    {
        echo "mapujSredniaCalosc";
        $this->mapujSredniaCalosc($id_analiza);
        echo "mapujSredniaDysleksja";
        $this->mapujSredniaDysleksja($id_analiza);
        echo "mapujSredniaLokalizacja";
        $this->mapujSredniaLokalizacja($id_analiza);
        echo "mapujSredniaPlec";
        $this->mapujSredniaPlec($id_analiza);
    }

    private function map()
    {
        
    }
    private function mapujSredniaCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_KLASA, null, null, 'Średnia');
    }

    private function mapujSredniaDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_KLASA, Parser::COLUMN_NAME_DYSLEKSJA, null, 'Średnia');
    }

    private function mapujSredniaLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_KLASA, Parser::COLUMN_NAME_LOKALIZACJA, null, 'Średnia');
    }

    private function mapujSredniaPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_CALOSC_KLASA_PLEC, [$id_analiza, $id_analiza]);
        $this->mapuj_dla_sredniej_klasa($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_KLASA, Parser::COLUMN_NAME_PLEC, null, 'Średnia');
    }
}