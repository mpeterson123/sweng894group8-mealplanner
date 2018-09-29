<?php

namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class MealPlan {
  private $startDate,
    $endDate;


  public function __construct($startDate, $endDate) {
    $this->startDate = $startDate;
    $this->endDate = $endDate;
  }

  public function addMeal($meal) {
    if($meal == ''){
        throw new \Exception(
            "Meal name cannot be empty", 1);
          }

  }

  public function removeMeal($meal) {
    if($name == ''){
        throw new \Exception(
            "Meal name cannot be empty", 1);
          }

  }

  public function setStartDate($startDate) {
    $this->startDate = $startDate;
  }

  public function setEndDate($endDate) {
    $this->endDate = $endDate;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function getEndDate() {
    return $this->endDate;
  }
}
?>
