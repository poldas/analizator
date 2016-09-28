<?php
namespace App\Logika\Analizator\Wykres;
use App\Logika\Analizator\Wykres\Parsers\Parser;
use App\Logika\Analizator\Wykres\Parsers\SredniaKlasyParser;
use App\Logika\Analizator\Wykres\Parsers\SredniaZadaniaParser;
use App\Logika\Analizator\Wykres\Parsers\SredniaObszarParser;
use App\Logika\Analizator\Wykres\Parsers\SredniaCzestoscParser;
class ChartBuilder implements ChartBuilderInterface {

    /**
     * @var Chart
     */
    private $chart;

    /*
     * Id analizy dla której budowany jest wykres
     */
    private $analiza_id;
    private $chartType;
    private $parsedData;
    private $builder;

    public function __construct()
    {
        $this->chart = new Chart();
    }

    public function setId($analiza_id)
    {
        $this->analiza_id = $analiza_id;
    }
    public function getChart()
    {
        $this->getParsedData();
        return $this->parsedData;
    }

    public function addToRender($chartType)
    {
        $this->chartType[] = $chartType;
    }

    private function getParsedData()
    {
        foreach ($this->chartType as $chartType) {
            $this->setBuilderByType($chartType);
            $this->builder->parseDataToChart($this->analiza_id);
            $this->parsedData[] = $this->builder->getResult();
        }
    }

    private function setBuilderByType($chartType)
    {
        switch ($chartType) {
            case Parser::TYP_SREDNIA:
                $this->setBuilder(new SredniaKlasyParser());
                break;
            case Parser::TYP_OBSZAR:
                $this->setBuilder(new SredniaObszarParser());
                break;
            case Parser::TYP_ZADANIE:
                $this->setBuilder(new SredniaZadaniaParser());
                break;
            case Parser::TYP_CZESTOSC:
                $this->setBuilder(new SredniaCzestoscParser());
                break;
            default:
                break;
        }
    }

    private function setBuilder(Parser $builder)
    {
        $this->builder = $builder;
    }
}