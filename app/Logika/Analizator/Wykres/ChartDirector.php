<?php
namespace App\Logika\Analizator\Wykres;

class ChartDirector
{
    private $builder;

    public function __construct($id = null)
    {
        $this->setBuilder(new ChartBuilder());
        if($id) $this->setId($id);
    }

    public function setId($analiza_id)
    {
        $this->builder->setId($analiza_id);
        return $this;
    }

    private function setBuilder(ChartBuilderInterface $builder)
    {
        $this->builder = $builder;
        return $this;
    }

    public function getCharts()
    {
        return $this->builder->getChart();
    }
    
    public function addToRender($chartType)
    {
        $this->builder->addToRender($chartType);
        return $this;
    }
}