<?php
namespace App\Logika\Analizator\Wykres;
use App\Logika\Analizator\Wykres\Parsers\Parser;
use App\Logika\Analizator\Wykres\Parsers\SredniaKlasyParser;
use App\Logika\Analizator\Wykres\Parsers\SredniaUmiejetnoscParser;
use App\Logika\Analizator\Wykres\Parsers\SredniaZadaniaParser;
use App\Logika\Analizator\Wykres\Parsers\SredniaObszarParser;
use App\Logika\Analizator\Wykres\Parsers\SredniaCzestoscParser;
class ChartBuilder implements ChartBuilderInterface {

    private $chart;
    private $id_analiza;
    private $chartType;
    private $parsedData = [];
    private $metadata = [];
    private $builder;
    private $klasy;
    private $obszary;
    private $umiejetnosci;
    private $zadania;

    public function __construct()
    {
        $this->chart = new Chart();
    }

    public function addToRender($chartType)
    {
        $this->chartType[] = $chartType;
    }

    public function setId($analiza_id)
    {
        $this->analiza_id = $analiza_id;
    }

    public function getChart()
    {
        if (!$this->chartType) return [];
        $this->prepareMetadata();
        $this->prepareParsedData();
        return array_values($this->parsedData);
    }

    private function prepareParsedData()
    {
        foreach ($this->chartType as $chartType) {
            $this->setBuilderByType($chartType);
            $this->builder->parseDataToChart($this->analiza_id);
            $this->parsedData = array_merge($this->builder->getResult(), $this->parsedData);
        }
    }

    private function setBuilderByType($chartType)
    {
        switch ($chartType) {
            case Parser::TYP_SREDNIA:
                $this->setBuilder(new SredniaKlasyParser());
                break;
            case Parser::TYP_UMIEJETNOSC:
                $this->setBuilder(new SredniaUmiejetnoscParser());
                break;
            case Parser::TYP_ZADANIE:
                $this->setBuilder(new SredniaZadaniaParser());
                break;
            case Parser::TYP_CZESTOSC:
                $this->setBuilder(new SredniaCzestoscParser());
                break;
            case Parser::TYP_OBSZAR:
                $this->setBuilder(new SredniaObszarParser());
                break;
            default:
                break;
        }
    }

    private function setBuilder(Parser $builder)
    {
        $this->builder = $builder;
    }

    private function prepareMetadata()
    {
//        $this->klasy =
    }
}