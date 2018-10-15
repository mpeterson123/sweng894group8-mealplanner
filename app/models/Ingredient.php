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
      $this->food = $fi;
      $this->quantity = $qty;
      $this->recipeId = $ri;
      $this->unit = $u;
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
      $this->recipeId = $recipeId;
    }

    public function getRecipeId() {
      return $this->recipeId;
    }

    public function setId($ingrId) {
      $this->id = $ingrId;
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
