<?php
namespace App\Logika\Analizator\Wykres\Parsers;
interface IParseToChartData {

    const CALOSC = 'szkoła';
    const TYP_SREDNIA = 'srednia';
    const TYP_OBSZAR = 'obszar';
    const TYP_ZADANIE = 'zadanie';
    const TYP_CZESTOSC = 'czestosc';
    const TYP_UMIEJETNOSC = 'umiejetnosc';

    const COLUMN_NAME_DYSLEKSJA = 'dysleksja';
    const COLUMN_NAME_KLASA = 'klasa';
    const COLUMN_NAME_PLEC = 'plec';
    const COLUMN_NAME_LOKALIZACJA = 'lokalizacja';
    const COLUMN_NAME_OBSZAR = 'obszar';
    const COLUMN_NAME_UMIEJETNOSC = 'umiejetnosc';
    const COLUMN_NAME_NR_ZADANIA = 'nr_zadania';
    const COLUMN_NAME_SREDNIA_PKT = 'srednia';
    const COLUMN_NAME_ILOSC_WYNIKOW = 'ilosc_wynikow';
    const COLUMN_NAME_SUMA = 'suma';

    public function getResult();
    public function addToRender($chartToRender);
    public function parseDataToChart($id_analiza);
}