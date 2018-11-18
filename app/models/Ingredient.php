<?php

namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Ingredient {
  private $id,
    $food,
    $quantity,
    $recipeId,
    $unit;

    public function __construct($fi, $qty, $ri, $u) {
      $this->setFood($fi);
      $this->setQuantity($qty);
      $this->setRecipeId($ri);
      $this->setUnit($u);
    }

    public function setFood($fi) {
      $this->food = $fi;
    }

    public function getFood() {
      return $this->food;
    }

    public function setQuantity($qty) {
      $this->quantity = $qty;
    }

    public function getQuantity() {
      return $this->quantity;
    }

    public function setRecipeId($recipeId) {
        if(!$recipeId)
        {
            throw new \Exception("RecipeId cannot be empty", 1);
        }

        $recipeId = intval($recipeId);
        if($recipeId < 1){
            throw new \Exception("RecipeId must be greater than 0", 1);
        }

        $this->recipeId = $recipeId;
    }

    public function getRecipeId() {
      return $this->recipeId;
    }

    public function setId($id) {
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

    public function getId() {
      return $this->id;
    }

    public function setUnit($u) {
      $this->unit = $u;
    }

    public function getUnit() {
      return $this->unit;
    }
}
?>
