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
	private $completedOn;

	public function setScaleFactor($newScale){
		$newScale = floatval($newScale);
		if(!$newScale || $newScale < 0.01 || !is_numeric($newScale))
		{
			$newScale = 1.0;
		}
		if($newScale > 500){
			$newScale = 500.0;
		}

		$this->scaleFactor = $newScale;
	}

	public function getScaleFactor(){
		return $this->scaleFactor;
	}

	public function isComplete(){
		return $this->isComplete;
	}

	public function setIsComplete($isComplete){
		$this->isComplete = $isComplete;
	}

	public function complete() {
		// TODO Remove this, alongside pdateStockAfterCreation in Recipe.php
		// if ($this->isComplete == FALSE){
		// 	$this->recipe->updateStockAfterCreation($this->scaleFactor);
		// }
		$this->completedOn = date('Y-m-d H:i:s');
		$this->isComplete = TRUE;
	}

	public function setCompletedOn($completedOn) {
		if ($completedOn && \DateTime::createFromFormat('Y-m-d H:i:s', $completedOn) == FALSE) {
			throw new \Exception("CompletedOn is not valid date", 1);
		}
		$this->completedOn = $completedOn;
	}

	public function getCompletedOn($formatted = false) {
		if($formatted){
			return date('m/d/Y, h:i A', strtotime($this->completedOn));
		}
		return $this->completedOn;
	}



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

		$id = intval($id);
        if($id < 1){
            throw new \Exception("Id must be greater than 0", 1);
        }

		$this->id = $id;
	}

	public function getDate($formatted = false){
		if($formatted){
			return date('m/d/Y', strtotime($this->date));
		}
		return $this->date;
	}

	public function setDate($newDate)
	{
		if (\DateTime::createFromFormat('Y-m-d', $newDate) == FALSE) {
			throw new \Exception("Date is not valid date", 1);
		}

		$this->date = $newDate;
	}

	public function getAddedDate($formatted = false){
		if($formatted){
			return date('m/d/Y, h:i A', strtotime($this->addedDate));
		}
		return $this->addedDate;
	}

	public function setAddedDate($addedDate){
		if (\DateTime::createFromFormat('Y-m-d H:i:s', $addedDate) == FALSE) {
			throw new \Exception("AddedDate is not valid date", 1);
		}

		$this->addedDate = $addedDate;
	}
}
?>
