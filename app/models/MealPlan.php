<?php

namespace Base\Models;

class MealPlan {
  private $startDate,
    $endDate;


  public function __construct($startDate, $endDate) {
    $this->startDate = $startDate;
    $this->endDate = $endDate;
  }

  public function addMeal($meal) {

  }

  public function removeMeal($meal) {

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
