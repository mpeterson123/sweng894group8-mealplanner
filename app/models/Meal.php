<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Meal{
	private $id;
	private $recipe;
	private $date;
	private $isComplete;
	private $addedDate;
	private $scaleFactor;

	public function __construct($recipe,$date,$scaleFactor){

		$this->setRecipe($recipe);
		$this->setDate($date);
		$this->setScaleFactor($scaleFactor);
		$this->isComplete = false;
		$this->addedDate = date('Y-m-d H-i-s');
	}

	public function setScaleFactor($newScale){
		$newScale = floatval($newScale);
		if(!$newScale)
		{
			$newScale = 1.0;
		}

		if(gettype($newScale) !== 'double' AND gettype($newScale) !== 'integer' AND gettype($newScale) !== 'float'){
				throw new \Exception("Scale must be a number", 1);
		}

		$this->scaleFactor = $newScale;
	}

	public function getScaleFactor(){
		return $this->scaleFactor;
	}

	public function isComplete(){
		return $this->isComplete;
	}

	public function complete(){

		if ($this->isComplete == FALSE){
			$this->recipe->updateStockAfterCreation($this->scaleFactor);
		}

		$this->isComplete = TRUE;
	}

	//public function markIncomplete(){
	//	$this->isComplete = false;
	//}

	public function getIngredientQuantity($anIngredientName){
		return $this->recipe->getIngredientByName($anIngredientName)->getQuantity() * $this->scaleFactor;
	}

	public function getRecipe(){
		return $this->recipe;
	}

	public function getRecipeId(){
		return $this->recipe->getId();
	}

	public function setRecipe($newRecipe){
		if(!$newRecipe instanceof Recipe)
		{
			throw new \Exception("Meal must reference a Recipe");
		}
		$this->recipe = $newRecipe;
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id)
	{
			if(!$id)
			{
					throw new \Exception("Id cannot be empty", 1);
			}

			if(gettype($id) !== 'integer'){
					throw new \Exception("Id must be an integer", 1);
			}

			$this->id = $id;
	}

	public function getDate(){
		return $this->date;
	}

	public function getAddedDate(){
		return $this->addedDate;
	}

	public function setDate($newDate)
	{
			if(!$newDate)
			{
					throw new \Exception("Date cannot be empty", 1);
			}
			if(!strtotime($newDate)){
					throw new \Exception("Date must be a timestamp", 1);
			}

			$this->date = $newDate;
	}
}
?>
