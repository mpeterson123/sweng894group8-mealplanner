<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Quantity Class
///////////////////////////////////////////////////////////////////////////////
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';


use Base\Models\Unit;

class Quantity
{
    private
        $value,
        $unit;

    public function __construct($value,$unit){
  		$this->value = $value;
        $this->unit = $unit;
    }

    public function convertTo($newUnit){
        if($this->unit->getBaseUnit() != $newUnit->getBaseUnit()){
            throw new \Exception("Units are incompatible", 1);
        }
        $baseEqvValue = $this->value*$this->unit->getBaseEqv();
        $this->value = $baseEqvValue/$newUnit->getBaseEqv();
        $this->unit = $newUnit;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($v)  {
        $this->value = $v;
    }

    public function getUnit()  {
        return $this->unit;
    }

    public function setUnit($u)  {
        $this->unit = $u;
    }
}

?>
