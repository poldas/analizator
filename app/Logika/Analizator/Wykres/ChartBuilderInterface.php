<?php
namespace App\Logika\Analizator\Wykres;

interface ChartBuilderInterface {

    public function getChart();

    public function addToRender($chartsTypeToRender);
}