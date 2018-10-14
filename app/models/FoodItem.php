<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class FoodItem {
    private
        $id,
        $name,
        $stock,
        $unit,
        $category,
        $unitsInContainer,
        $containerCost,
        $unitCost;

    public function setId($id)
    {
        if(!$id)
        {
            throw new \Exception("Id cannot be empty", 1);
        }

        if(gettype($id) !== 'integer'){
            throw new \Exception("Id must be an integer", 1);
        }

        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set food item name
     * @param string $name Food item name
     */
    public function setName($name):void{
        if($name == ''){
            throw new \Exception(
                "Food Item name cannot be empty", 1);
        }

        if(strlen($name) > 50){
            throw new \Exception(
                "Food Item name cannot be longer than 50 characters", 1);
        }

        $this->name = trim($name);
    }

    /**
     * Get food item name
     * @return string Food item name
     */
    public function getName():string{
        return $this->name;
    }

    /**
     * Set food item stock
     * @param  float $stock Current amount of food item in chosen units
     */
    public function setStock($stock){
        if($stock < 0)
        {
            throw new \Exception(
                "Stock cannot be a negative number", 1);
        }
        $this->stock = floatval($stock);
    }

    /**
     * Get food item stock
     * @return float Current amount of food item in chosen units
     */
    public function getStock():float{
        return $this->stock;
    }

    /**
     * Set food item's associated unit
     * @param Unit $unit Food item's unit
     */
    public function setUnit($unit):void
    {
        if(!$unit){
            throw new \Exception("Unit cannot be empty", 1);
        }
        $this->unit = $unit;
    }

    /**
     * Get food item's associated unit
     * @return Unit Food item's unit
     */
    public function getUnit():Unit{
        return $this->unit;
    }

    /**
     * Set food item's associated category
     * @param Category $category Food item's category
     */
    public function setCategory($category):void
    {
        $this->category = $category;
    }

    /**
     * Get food item's associated category
     * @return Category Food item's category
     */
    public function getCategory():Category{
        return $this->category;
    }

    /**
     * Get how many units a container of the food item has
     * @param float $unitsInContainer Units in container
     */
    public function setUnitsInContainer($unitsInContainer):void {
        if(!preg_match_all('/^\d+(\.\d+)?$/', $unitsInContainer)){
            throw new \Exception("UnitsInContainer must be numeric", 1);
        }

        if($unitsInContainer <= 0 || $unitsInContainer > 999.99){
            throw new \Exception('Units in container must be a positive number below 999.99', 1);
        }
        $this->unitsInContainer = floatval($unitsInContainer);
    }

    /**
     * Get how many units a container of the food item has
     * @return float Units in container
     */
    public function getUnitsInContainer():float{
        return $this->unitsInContainer;
    }

    /**
     * Get cost of a container of the food item
     * @param float $containerCost Food item container's cost
     */
    public function setContainerCost($containerCost):void {
        if(!preg_match_all('/^\d+(\.\d+)?$/', $containerCost)){
            throw new \Exception("ContainerCost must be numeric", 1);
        }

        if($containerCost <= 0 || $containerCost > 9999.99){
            throw new \Exception('Container cost must be a positive number below 10,000', 1);
        }
        $this->containerCost = floatval($containerCost);
    }

    /**
     * Get cost of a container of the food item
     * @return float Food item container's cost
     */
    public function getContainerCost():float{
        return $this->containerCost;
    }

    /**
     * Calculate cost of food item per unit chosen, based on container cost
     */
    public function setUnitCost():void {
        $this->unitCost = $this->containerCost/$this->unitsInContainer;
    }

    /**
     * Get cost of food item per unit chosen, based on container cost
     * @return float Food item's unit cost
     */
    public function getUnitCost():float {
        if($this->containerCost <= 0 || $this->unitsInContainer <= 0){
            throw new \Exception("Container cost and units in container must be valid values", 1);
        }
        return $this->containerCost/$this->unitsInContainer;
    }

}
