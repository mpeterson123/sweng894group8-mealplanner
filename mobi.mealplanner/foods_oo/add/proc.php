<?php

require_once('class/DatabaseHandler.class.php');
require_once('class/FoodItemRepo.class.php');
require_once('class/FoodItem.class.php');
require_once('class/FoodItemController.class.php');

try{
  $dbh = new DatabaseHandler();
  $foodItemRepo = new FoodItemRepo($dbh);
  $foodItem = new FoodItem($_REQUEST['name'], $_REQUEST['cost']);
  $foodItemRepo->add($foodItem);
  header('Location: /foods_oo/');
}
catch (Exception $e)
{
  die('Error: '.$e->getMessage());
}
