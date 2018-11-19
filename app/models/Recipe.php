<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Recipe{
	private $id;
	private $name;
	private $directions;
	private $servings;
	private $ingredients;
	private $source;
	private $notes;



	public function __construct($theName='',$theDirections='',$theServings='',$theSource='',$theNotes=''){
		$this->setName($theName);
		$this->setDirections($theDirections);
		$this->setServings($theServings);
		$this->ingredients = array();
		$this->setSource($theSource);
		$this->setNotes($theNotes);
	}

	public function addIngredient($anIngredient){
		$this->ingredients[] = $anIngredient;
	}

	public function removeIngredient($anIngredientName){
		foreach ($this->ingredients as $index => $ingredient) {
			if($ingredient->getFood()->getName() == $anIngredientName){
				unset($this->ingredients[$index]);
			}
		}
	}

	public function swapIngredient($old,$new){
		$this->removeIngredient($old);
		$this->addIngredient($new);
	}

	public function getIngredientByName($anIngredientName){
		for($i=0;$i<count($this->ingredients);$i++){
			if($this->ingredients[$i]->getFood()->getName() == $anIngredientName)
				return $this->ingredients[$i];
		}

		return null;
	}

	public function getIngredientById($anIngredientId) {
		foreach ($this->ingredients as $index => $ingredient) {
			if($ingredient->getId() == $anIngredientId){
				return $ingredient;
			}
		}

		return null;
	}

	public function getDirections(){
		return $this->directions;
	}

	public function getId(){
			return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getServings(){
		return $this->servings;
	}

	public function getSource(){
		return $this->source;
	}

	public function getNotes(){
		return $this->notes;
	}

	public function getIngredientQuantity($anIngredientName){
		return $this->getIngredientByName($anIngredientName)->getQuantity();
	}

	public function setDirections($directions){
		if(gettype($directions) !== 'string' || strlen($directions) > 65535){
			throw new \Exception("Directions must be a string 65535 characters or shorter", 1);
		}

		$this->directions = trim($directions);
	}

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

	public function setName($name){
		if(gettype($name) !== 'string' || strlen($name) > 128){
			throw new \Exception("Notes must be a string from 1-128 characters", 1);
		}

		$this->name = trim($name);
	}

	public function setNotes($notes){
		if(gettype($notes) !== 'string' || strlen($notes) > 128){
			throw new \Exception("Notes must be a string 128 characters or shorter", 1);
		}

		$this->notes = trim($notes);
	}

	public function setServings($servings){
		$this->servings = $servings;
	}

	public function setSource($source){
		if(gettype($source) !== 'string'){
			throw new \Exception("Source must be a string", 1);
		}

		if(strlen($source) > 64){
			throw new \Exception(
				"Source cannot be longer than 64 characters", 1);
		}

		$this->source = trim($source);
	}

	public function getIngredients() {
		return $this->ingredients;
	}

	/**
	* Update the attributes of the ingredient
	* @param object $anIngredient The ingredient object with new data
	*/

	public function updateIngredient($anIngredient) {
		$currIngredient = $this->getIngredientById($anIngredient->getId());

		if($currIngredient != null) {
			$currIngredient->setFood($anIngredient->getFood());
			$currIngredient->setQuantity($anIngredient->getQuantity());
			$currIngredient->setUnit($anIngredient->getUnit());

			return true;
		}
		else {
			return false;
		}

	}

	// /**
	//  * Update the stock of user's food items after a recipe is executed with a given scaleFactor
	//  * @param integer $scale scale of the recipe to subtract
	//  */
	// public function updateStockAfterCreation($scale){
	// 	// Default stock to 1 if none is given
	// 	if ($scale == NULL){
	// 		$scale = 1.0;
	// 	}
	//
	// 	for($i=0;$i<count($this->ingredients);$i++){
	// 		// Get Food of Ingredient
	//
	// 		$ingredientFood = $this->ingredients[$i]->getFood();
	//
	// 		//Get Current Stock of food
	// 		$currentStock = $ingredientFood->getStock();
	//
	// 		// Get how much the ingredient requires in the recipe
	// 		$this->ingredients[$i]->getQuantity()->convertTo($ingredientFood->getUnit());
	// 		$ingredientQty = $this->ingredients[$i]->getQuantity()->getValue();
	// 		// Set the stock of the user's food. This will be the current stock
	// 		$ingredientFood->setStock($currentStock - ($scale * $ingredientQty));
	//
	// 		//Save food item
	// 		// DONE IN MEALS CONTROLLER
	// 	}
	// }
}
?>
