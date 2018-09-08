<?php

class FoodItemRepo {
    private $dbh;

    public function __construct($dbh){
        $this->dbh = $dbh;
    }

    public function add($name, $cost){
        $mysqli = $this->dbh->getDB();

        $insert_stmt = $mysqli->prepare("INSERT INTO food (name, unitcost, userid) VALUES (?, ?, 1)");
        $insert_stmt->bind_param(array(
              $name,
              $cost
          ))
          ->execute();
    }
}
