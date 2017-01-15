<?php
namespace App\Logika\Analizator\Wykres\Parsers;

use Illuminate\Support\Facades\DB;

abstract class Parser implements IParseToChartData {
    protected $mapowanie_podzialu_wykresu = [
        'T' => 'dysleksja',
        'N' => 'bez dysleksji',
        'm' => 'miasto',
        'w' => 'wieś',
        'K' => 'kobieta',
        'M' => 'mężczyzna'
    ];

    protected $wynik = [];
    protected $id_analiza;
    protected $chatsTypeToRender;

    public function setIdAnaliza($id_analiza) {
        $this->id_analiza = $id_analiza;
    }

    abstract function parseDataToChart();

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

    protected function prepareDataset(&$dataset, $row, $value, $label, $wykres1 = null, $wykres2 = null, $series_param_type = null)
    {
        if(!empty($series_param_type)) {
            $series_name_flag = $row[$series_param_type];
            $series_type = $series_param_type;
        } else {
            $series_type = $series_name_flag = 'bezpodzialu';
        }
        $series_name = $this->translateSeriesName($series_name_flag);
        $chart_id = $this->getChartId($wykres1, $wykres2, $series_type);
        $chart_name = $this->getChartName($wykres1, $wykres2, $series_type);
        $translated_series = $this->translateSeriesType($series_type);
        $dataset[$chart_id]['id'] = $chart_id;
        $dataset[$chart_id]['id_analiza'] = $this->id_analiza;
        $dataset[$chart_id]['name'] = $chart_name;
        $dataset[$chart_id]['series'][$series_name]['data'][] = [$label, $value];
        $dataset[$chart_id]['series'][$series_name]['name'] = $series_name;
        $dataset[$chart_id]['labels'][$label] = $label;
        $dataset[$chart_id]['tags'][$translated_series] = $translated_series;
        return $chart_id;
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

    protected function translateSeriesName($series_name)
    {
        if (isset($this->mapowanie_podzialu_wykresu[$series_name])) {
            return $this->mapowanie_podzialu_wykresu[$series_name];
        }
        return 'bez podziału';
    }
}