<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Unit Class
///////////////////////////////////////////////////////////////////////////////
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Unit
{
    private
        $id,
        $name,
        $baseEqv;

    public function setId($id)
    {
        if(!$id)
        {
            throw new \Exception("Id cannot be empty", 1);
        }

        $id = intval($id);
        if($id < 1){
            throw new \Exception("Id must be greater than 0", 1);
        }

        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    //////////
    // Name //
    //////////
    public function setName($name){
        $name = trim($name);

        /*
         * Regex rules:
         * - Only letters and parentheses
         * - Case insensitive
         * - From 1-20 characters
         */
        $regex = '/^[a-z\(\) ]{1,20}$/i';

        if(!preg_match_all($regex, $name, $matches)){
            throw new \Exception(
                "Unit name must only contain letters and parentheses, and must be 1-20 characters in length", 1);
        }

        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    //////////////////
    // Abbreviation //
    //////////////////
    public function setAbbreviation($abbreviation){
        $abbreviation = trim($abbreviation);

        /*
         * Regex rules:
         * - Only letters
         * - Case insensitive
         * - From 1-4 characters
         */
        $regex = '/^[a-z ]{1,5}$/i';

        if(!preg_match_all($regex, $abbreviation, $matches)){
            throw new \Exception(
                "Base unit must alphabetical, and must be 1-5 characters in length", 1);
        }

        $this->abbreviation = $abbreviation;
    }

    public function getAbbreviation(){
        return $this->abbreviation;
    }

    //////////////
    // BaseUnit //
    //////////////

    public function setBaseUnit($baseUnit){
        $baseUnit = trim($baseUnit);

        /*
         * Regex rules:
         * - Only letters
         * - Case insensitive
         * - From 1-4 characters
         */
        $regex = '/^[a-z ]{1,5}$/i';

        if(!preg_match_all($regex, $baseUnit, $matches)){
            throw new \Exception(
                "Base unit must alphabetical, and must be 1-5 characters in length", 1);
        }

        $this->baseUnit = $baseUnit;
    }

    public function getBaseUnit(){
        return $this->baseUnit;
    }


    /////////////
    // BaseEqv //
    /////////////

    public function setBaseEqv($eqv)
    {
        $eqv = floatval($eqv);
        if($eqv <= 0){
            throw new \Exception("Base Equivalence must be greater than 0", 1);
        }
        $this->baseEqv = $eqv;
    }
    public function getBaseEqv(){
      return $this->baseEqv;
    }
}

?>
