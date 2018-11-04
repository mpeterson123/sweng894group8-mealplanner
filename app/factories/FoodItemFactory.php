<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\Factory;
use Base\Models\FoodItem;
use Base\Repositories\CategoryRepository;
use Base\Repositories\UnitRepository;

/**
 * Handles FoodItem model instantiation
 */
class FoodItemFactory extends Factory {

    private $categoryRepository,
        $unitRepository;

    public function __construct($categoryRepository, $unitRepository){
        $this->categoryRepository = $categoryRepository;
        $this->unitRepository = $unitRepository;
    }

    /**
     * Creates a new instance of FoodItem model
     * @param  array    $foodItemArray A food item's properties
     * @return FoodItem                A food item object
     */
    public function make(array $foodItemArray):FoodItem
    {
        $category = $this->categoryRepository->find($foodItemArray['categoryId']);
        $unit = $this->unitRepository->find($foodItemArray['unitId']);

        $foodItem = new FoodItem();
        if(isset($foodItemArray['id'])){
            $foodItem->setId($foodItemArray['id']);
        }
        $foodItem->setName($foodItemArray['name']);
        $foodItem->setStock(floatval($foodItemArray['stock']));
        $foodItem->setCategory($category);
        $foodItem->setUnit($unit);
        $foodItem->setUnitsInContainer(floatval($foodItemArray['unitsInContainer']));
        $foodItem->setContainerCost(floatval($foodItemArray['containerCost']));
        $foodItem->setUnitCost();

        return $foodItem;
    }
}
