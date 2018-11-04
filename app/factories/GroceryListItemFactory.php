<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\Factory;
use Base\Models\GroceryListItem;
use Base\Repositories\FoodItemRepository;

/**
 * Handles GroceryListItem model instantiation
 */
class GroceryListItemFactory extends Factory {

    private $foodItemRepository;

    public function __construct($foodItemRepository){
        $this->foodItemRepository = $foodItemRepository;
    }

    /**
     * Creates a new instance of GroceryListItem model
     * @param  array    $groceryListItemArray A grocery list item's properties
     * @return GroceryListItem                A grocery list item object
     */
    public function make(array $groceryListItemArray):GroceryListItem
    {
        $foodItem = $this->foodItemRepository->find($groceryListItemArray['foodItemId']);

        $groceryListItem = new GroceryListItem();
        if(isset($groceryListItemArray['id'])){
            $groceryListItem->setId($groceryListItemArray['id']);
        }
        $groceryListItem->setFoodItem($foodItem);
        $groceryListItem->setAmount(floatval($groceryListItemArray['amount']));
        return $groceryListItem;
    }
}
