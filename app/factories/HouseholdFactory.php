<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Household;

/**
 * Handles Household model instantiation
 */
class HouseholdFactory {

    /**
     * Creates a new instance of Household model
     * @param  array    $householdArray A household's properties
     * @return Household                A household object
     */
    public function make(array $householdArray):Household
    {
        $household = new Household();
        if(isset($householdArray['id'])){
            $household->setId($householdArray['id']);
        }
        $household->setName($householdArray['name']);

        return $household;
    }
}
