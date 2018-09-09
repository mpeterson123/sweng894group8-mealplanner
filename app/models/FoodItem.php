<?php
namespace Base\Models;

class FoodItem {
    private $name,
        $cost;

    public function __construct($name, $category , $unit, $stock = 0, $cost = 0.00){
        $this->name = $name;
        $this->category = $category;
        $this->unit = $unit;
        $this->stock = $stock;
        $this->cost = $cost;
    }

    public function setName($name){
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

    public function getName(){
        return $this->name;
    }

    public function setStock($stock){
        if($stock < 0)
        {
            throw new \Exception(
                "Stock cannot be a negative number", 1);
        }
        $this->stock = $stock;
    }

    public function getStock(){
        return $this->stock;
    }
}
