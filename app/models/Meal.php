<?php
namespace Base\Models;

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
	public function setScale($newScale){
		$this->scale = $newScale;
	}
	public function isComplete(){
		return $this->isComplete;
	}
	public function markComplete(){
		$this->isComplete = true;
	}
	public function markIncomplete(){
		$this->isComplete = false;
	}
}
?>
