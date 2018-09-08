<?php

class FoodItem {
    private $name,
        $cost;

    public function __construct($name, $cost){
        $this->name = $name;
        $this->cost = $cost;
    }
}
