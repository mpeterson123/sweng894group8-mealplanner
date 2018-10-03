<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\FoodItem;
use Base\Repositories\CategoryRepository;
use Base\Repositories\UnitRepository;

class FoodItemFactory {

    private $db;

    public function make($foodItemArray)
    {
        $category = (new CategoryRepository($this->db))->find($foodItemArray['category_id']);
        $unit = (new UnitRepository($this->db))->find($foodItemArray['unit_id']);

        $foodItem = new FoodItem();
        if(isset($foodItemArray['id'])){
            $foodItem->setId($foodItemArray['id']);
        }
        $foodItem->setName($foodItemArray['name']);
        $foodItem->setStock(floatval($foodItemArray['stock']));
        $foodItem->setCategory($category);
        $foodItem->setUnit($unit);
        $foodItem->setUnitsInContainer(floatval($foodItemArray['units_in_container']));
        $foodItem->setContainerCost(floatval($foodItemArray['container_cost']));
        $foodItem->setUnitCost();

        return $foodItem;
    }

    public function __construct($db){
        $this->db = $db;
    }

}
