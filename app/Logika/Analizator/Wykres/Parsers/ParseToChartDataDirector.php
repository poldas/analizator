<?php
namespace App\Logika\Analizator\Wykres\Parsers;

use App\Logika\Analizator\Wykres\Parsers\Parser;

class ParseToChartDataDirector {
    private $builder;
    private $analiza_id;
    private $chartType;
    private $parsedData;

    public function setBuilderByType($chartType)
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
                $this->setBuilder(new SredniaKlasyParser());
                break;
            default:
                break;
        }
    }

    private function setBuildersByType()
    {
        foreach ($this->chartType as $chartType) {
            $this->setBuilderByType($chartType);
            $this->builder->parseDataToChart($this->analiza_id);
            $this->parsedData[] = $this->builder->getResult();
        }
    }
    public function setAnalizeId($analiza_id) {
        $this->analiza_id = $analiza_id;
    }
    
    private function setBuilder(IParseToChartData $builder)
    {
        $this->builder = $builder;
    }

    public function setDataToRender($chartType)
    {
        $this->chartType = $chartType;
    }
    public function getResult()
    {
        $this->setBuildersByType();
        return $this->parsedData;
    }
}