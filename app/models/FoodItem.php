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
        $this->id = $id;
    }

    public function getId(){
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

        if(strlen($name) > 20){
            throw new \Exception(
                "Food Item name cannot be longer than 20 characters", 1);
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
        $this->stock = $stock;
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
    public function setUnitsInContainer($unitsInContainer):void
    {
        $this->unitsInContainer = trim($unitsInContainer);
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
    public function setContainerCost($containerCost):void
    {
        $this->containerCost = trim($containerCost);
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
    public function setUnitCost():void
    {
        $this->unitCost = $this->containerCost/$this->unitsInContainer;
    }

    /**
     * Get cost of food item per unit chosen, based on container cost
     * @return float Food item's unit cost
     */
    public function getUnitCost():float{
        return $this->unitCost;
    }

}
