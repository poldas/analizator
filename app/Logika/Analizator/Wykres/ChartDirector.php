<?php
namespace App\Logika\Analizator\Wykres;

class ChartDirector
{
    private $builder;
    private $id_analiza;

    public function __construct($id = null)
    {
        $this->setBuilder(new ChartBuilder());
        if($id) $this->setId($id);
    }

    public function setId($id_analiza)
    {
        $this->id_analiza = $id_analiza;
        $this->builder->setId($this->id_analiza);
        return $this;
    }

    public function setBuilder(ChartBuilderInterface $builder)
    {
        $this->builder = $builder;
        $this->setId($this->id_analiza);
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