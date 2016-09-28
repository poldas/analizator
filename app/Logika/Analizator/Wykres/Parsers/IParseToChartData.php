<?php
namespace App\Logika\Analizator\Wykres\Parsers;
interface IParseToChartData {

    public function getResult();
    public function addToRender($chartToRender);
    public function parseDataToChart($id_analiza);
}