<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Quantity Class
///////////////////////////////////////////////////////////////////////////////
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';


use Base\Models\Unit;

/**
 * Represents an ingredient's quantity
 */
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

    public function setValue($value)  {
        $value = floatval($value);
		if(!$value || $value < 0.01 || !is_numeric($value))
		{
			throw new \Exception("Value must be number >= 0.01", 1);
		}

		$this->value = $value;
    }

    public function getUnit()  {
        return $this->unit;
    }

    public function setUnit($u)  {
        if(!$u instanceof Unit)
		{
			throw new \Exception("Quantity must reference a Unit");
		}
		$this->unit = $u;
    }
}

?>
