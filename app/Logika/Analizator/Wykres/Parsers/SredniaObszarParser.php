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
        echo "mapujSredniaObszarCalosc";
        $this->mapujSredniaObszarCalosc($id_analiza);
        echo "mapujSredniaObszarDysleksja";
        $this->mapujSredniaObszarDysleksja($id_analiza);
        echo "mapujSredniaObszarLokalizacja";
        $this->mapujSredniaObszarLokalizacja($id_analiza);
        echo "mapujSredniaObszarPlec";
        $this->mapujSredniaObszarPlec($id_analiza);

        echo "mapujSredniaUmiejetnoscCalosc";
        $this->mapujSredniaUmiejetnoscCalosc($id_analiza);
        echo "mapujSredniaUmiejetnoscDysleksja";
        $this->mapujSredniaUmiejetnoscDysleksja($id_analiza);
        echo "mapujSredniaUmiejetnoscLokalizacja";
        $this->mapujSredniaUmiejetnoscLokalizacja($id_analiza);
        echo "mapujSredniaUmiejetnoscPlec";
        $this->mapujSredniaUmiejetnoscPlec($id_analiza);
    }

    private function mapujSredniaObszarCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_KLASA, Parser::COLUMN_NAME_OBSZAR, null, null, 'obszar całość');
        return $chartData;
    }

    private function mapujSredniaObszarDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_DYSLEKSJA, Parser::COLUMN_NAME_KLASA, 'obszar dysleksja');
        return $chartData;
    }

    private function mapujSredniaObszarLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_LOKALIZACJA, Parser::COLUMN_NAME_KLASA, 'obszar lokalizacja');
        return $chartData;
    }

    private function mapujSredniaObszarPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_PLEC, Parser::COLUMN_NAME_KLASA, 'obszar plec');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscCalosc($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_CALOSC, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_UMIEJETNOSC, null, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscDysleksja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_DYSLEKSJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_UMIEJETNOSC, Parser::COLUMN_NAME_DYSLEKSJA, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscLokalizacja($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_LOKALIZACJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_UMIEJETNOSC, Parser::COLUMN_NAME_LOKALIZACJA, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscPlec($id_analiza)
    {
        $dane_db = $this->dbSelect(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_PLEC, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, Parser::COLUMN_NAME_SREDNIA_PKT, Parser::COLUMN_NAME_UMIEJETNOSC, Parser::COLUMN_NAME_PLEC, Parser::COLUMN_NAME_OBSZAR, Parser::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }
}