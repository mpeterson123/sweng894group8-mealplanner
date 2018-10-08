<?php

namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Ingredient {
  private $food,
    $quantity,
    $recipeId;

    public function __construct($fi, $qty) {
      $this->food = $fi;
      $this->quantity = $qty;
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
}

?>
