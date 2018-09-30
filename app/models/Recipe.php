<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Recipe{
	private $id;
	private $name;
	private $description;
	private $servings;
	private $ingredients;
	private $source;
	private $notes;

	public function __construct($theName,$theDescription='',$theServings,$theSource='',$theNotes=''){
		$this->name = $theName;
		$this->description = $theDescription;
		$this->servings = $theServings;
		$this->ingredients = array();
		$this->source = $theSource;
		$this->notes = $theNotes;
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
		removeIngredient($old);
		addIngredient($new);
	}

	public function getIngredientByName($anIngredientName){
		for($i=0;$i<count($this->ingredients);$i++){
			if($this->ingredients[$i]->getFood()->getName() == $anIngredientName)
				return $this->ingredients[$i];
		}
	}

	public function getDescription(){
		return $this->description;
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

	public function setDescription($desc){
		$this->description = $desc;
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

}
?>
