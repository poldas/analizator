<?php
namespace App\Logika\Analizator\Wykres\Parsers;

use Illuminate\Support\Facades\DB;

abstract class Parser implements IParseToChartData {

    protected $name_czestosc = 'Częstość wyników';
    protected $name_srednia = 'Średnia';
    protected $name_obszar = 'Obszar';
    protected $wynik;

    protected $mapowanie_podzialu_wykresu = [
        'T' => 'dysleksja',
        'N' => 'bez dysleksji',
        'm' => 'miasto',
        'w' => 'wieś',
        'K' => 'kobieta',
        'M' => 'mężczyzna',
        'wszystko' => 'całość'
    ];

    protected $mapowanie_nazw_podzialu = [
        'T' => 'wg dyslektyków',
        'N' => 'wg dyslektyków',
        'm' => 'wg lokalizacji',
        'w' => 'wg lokalizacji',
        'K' => 'wg płci',
        'M' => 'wg płci',
        'wszystko' => ''
    ];
    protected $chatsTypeToRender;

    abstract function parseDataToChart($id);
    abstract function getResult();

    protected function dbSelect($sql, $options) {
        return DB::select($sql, $options);
    }
    public function addToRender($chartToRender)
    {
        $this->chatsTypeToRender[] = $chartToRender;
    }
    protected function addNewChart($data)
    {
        $this->wynik[] = $data;
    }

    protected function getChartName($wykres_obszar, $wykres_klasa, $series_name)
    {
        return strtolower('obszar'.$wykres_obszar.'klasa'.$wykres_klasa.$series_name);
    }


    protected function getChartNameFromColumnName($column_name)
    {
        if (!$column_name) return '';
        if ($column_name == self::COLUMN_NAME_DYSLEKSJA) return ' wg dysleksji';
        if ($column_name == self::COLUMN_NAME_LOKALIZACJA) return ' wg lokalizacji';
        if ($column_name == self::COLUMN_NAME_PLEC) return ' wg płci';
        if ($column_name == self::COLUMN_NAME_DYSLEKSJA) return ' dysleksja';
    }

    protected function generateChartName($name, $wykresy_podzial, $series_name_kategorii)
    {
        return $name. ' '.$this->mapName($wykresy_podzial). ' '. $series_name_kategorii;
    }

    protected function mapName($wykresy_podzial)
    {
        if (!$wykresy_podzial) return '';
        if ($wykresy_podzial == 'szkola') return 'szkoła';
        else return 'klasa '.$wykresy_podzial;
    }

    protected function mapuj_nazwe_do_wykresu($podzial) {
        $nazwa_podzial = '';
        if (in_array($podzial, array_keys($this->mapowanie_nazw_podzialu))) {
            $nazwa_podzial = $this->mapowanie_nazw_podzialu[$podzial];
        }
        return $nazwa_podzial;
    }

    protected function getSeriesName($podzial)
    {
        $nazwa_podzial = 'wszystko';
        if (in_array($podzial, array_keys($this->mapowanie_podzialu_wykresu))) {
            $nazwa_podzial = $this->mapowanie_podzialu_wykresu[$podzial];
        }
        return $nazwa_podzial;
    }
}