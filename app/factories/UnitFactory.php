<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\Factory;
use Base\Models\Unit;

/**
 * Handles Unit model instantiation
 */
class UnitFactory extends Factory {

    /**
     * Creates a new instance of Unit model
     * @param  array    $unitArray A unit's properties
     * @return Unit                A unit object
     */
    public function make(array $unitArray):Unit
    {
        $unit = new Unit();
        if(isset($unitArray['id'])){
            $unit->setId(intval($unitArray['id']));
        }
        $unit->setName($unitArray['name']);
        $unit->setAbbreviation($unitArray['abbreviation']);
        $unit->setBaseUnit($unitArray['baseUnit']);
        $unit->setBaseEqv(floatval($unitArray['baseEqv']));

        return $unit;
    }



}
