<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Meal{
	private $recipe;
	private $date;
	private $isComplete;
	private $addedDate;
	private $scale;

	public function __construct($r,$d,$s){
		$this->recipe = $r;
		$this->date = $d;
		$this->isComplete = false;
		$this->scale = $s;
		$this->addedDate = date('Y-m-d');
	}
	public function scale($newScale){
		$this->scale = $newScale;
	}
	public function isComplete(){
		return $this->isComplete;
	}
	public function complete(){
		$this->isComplete = true;
	}
	public function markIncomplete(){
		$this->isComplete = false;
	}
	public function getIngredientQuantity($anIngredientName){
				return $this->recipe->getIngredientByName($anIngredientName)->getQuantity() * $this->scale;
	}
	public function getRecipe(){
		return $this->recipe;
	}
	public function setRecipe($aRecipe){
		$this->recipe = aRecipe;
	}
}
?>
