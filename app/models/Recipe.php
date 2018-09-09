<?php
namespace Base\Models;

class Recipe{
	private $name;
	private $description;
	private $yield;
	private $ingredients;
	private $source;
	private $notes;

	public __construct($theName,$theDescription,$theYield,$theSource,$theNotes){
		$this->name = $theName;
		$this->description = $theDescription;
		$this->yield = $theYield;
		$this->ingredients = array();
		$this->source = $theSource;
		$this->notes = $theNotes;
	}
	public function addIngredient($anIngredient){
		$this->ingredient[] = $anIngredient;
	}
	public function removeIngredient($anIngredient){
		for($i=0;$i<$this->ingredients;$i++){
			if($this->ingredients[$i] == $anIngredient)  // Or use equality method
				unset($this->ingredients[$i]);
		}
	}
	public function swapIngredient($old,$new){
		removeIngredient($old);
		addIngredient($new);
	}
?>
