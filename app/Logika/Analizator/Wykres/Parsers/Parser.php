<?php
namespace App\Logika\Analizator\Wykres\Parsers;

use Illuminate\Support\Facades\DB;

abstract class Parser implements IParseToChartData {

    protected $wynik = [];

    protected $chatsTypeToRender;

    abstract function parseDataToChart($id);

    public function getResult()
    {
        return $this->wynik;
    }

    protected function dbSelect($sql, $options) {
        return DB::select($sql, $options);
    }

    public function addToRender($chartToRender)
    {
        $this->chatsTypeToRender[] = $chartToRender;
    }

    protected function addNewChart($data)
    {
        $this->wynik = array_merge($data, $this->wynik);
    }

    protected function translateKlasa($wykres_klasa)
    {
        if ($wykres_klasa == self::CALOSC) return 'szkoła';
        return 'klasa '.$wykres_klasa;
    }

    protected function translateSeriesType($seriesType)
    {
        switch ($seriesType) {
            case self::COLUMN_NAME_DYSLEKSJA: return 'wg dysleksji';
            case self::COLUMN_NAME_LOKALIZACJA: return 'wg lokalizacji';
            case self::COLUMN_NAME_PLEC: return 'wg płci';
            default: return 'bez podziałów';
        }
    }
}