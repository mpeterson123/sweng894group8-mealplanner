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
		for($i=0;$i<count($this->ingredients);$i++){
			if($this->ingredients[$i]->getName() == $anIngredientName)
				unset($this->ingredients[$i]);
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

	public function setDirections($dirs){
		$this->directions = $dirs;
	}

	public function setId($id)
	{
			$this->id = $id;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function setNotes($notes){
		$this->notes = $notes;
	}

	public function setServings($servings){
		$this->servings = $servings;
	}

	public function setSource($source){
		$this->source = $source;
	}

	public function getIngredients() {
		return $this->ingredients;
	}

	public function updateIngredient($name) {

	}

	/**
	 * Update the stock of user's food items after a recipe is executed with a given scaleFactor
	 * @param integer $scale scale of the recipe to subtract
	 */
	public function updateStockAfterCreation($scale){
		// Default stock to 1 if none is given
		if ($scale == NULL){
			$scale = 1.0;
		}

		for($i=0;$i<count($this->ingredients);$i++){
			// Get Food of Ingredient

			$ingredientFood = $this->ingredients[$i]->getFood();

			//Get Current Stock of food
			$currentStock = $ingredientFood->getStock();

			// Get how much the ingredient requires in the recipe
			$ingredientQty = $this->ingredients[$i]->getQuantity();

			// Set the stock of the user's food. This will be the current stock
			$ingredientFood->setStock($currentStock - ($scale * $ingredientQty));

			//Save food item
			// DONE IN MEALS CONTROLLER
		}
	}
}
?>
