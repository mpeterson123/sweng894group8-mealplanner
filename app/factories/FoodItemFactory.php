<?

namespace Base\Factories;

require_once __DIR__.'/../models/FoodItem.php';


use Base\Models\FoodItem;


class FoodItemFactory {
    public static function make($foodArray)
    {
        $foodItem = new FoodItem();
        $foodItem->setId($foodArray['id']);
        $foodItem->setName($foodArray['name']);
        $foodItem->setCategory($category);
        $foodItem->setUnit($unit);
        $foodItem->setStock($stock);
        $foodItem->setCost($cost);

        return $foodItem;
    }

    public function __construct(){


    }
}
