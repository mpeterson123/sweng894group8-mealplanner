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

	public function updateStockAfterCreation($scale){
		// Default stock to 1 if none is given
		if ($scale == NULL){
			$scale = 1.0;
		}

		for($i=0;$i<count($this->ingredients);$i++){
			$ingredientFood = $this->ingredient[$i]->getFood();
			$currentStock = $ingredientFood->getStock();
			$ingredientQty = $this->ingredients[$i]->getQuantity();
			$ingredientFood->setStock($scale * ($currentStock - $ingredientQty));

			//Save food
			//$this->foodItemRepository->save($ingredientFood);
		}
	}
}
?>
