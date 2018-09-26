<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Unit Class
///////////////////////////////////////////////////////////////////////////////
namespace Base\Models;

class Unit
{
    private
        $id,
        $name,
        $baseEqv;

    public function __construct($id,$name,$baseEqv){
  		$this->id = $id;
      $this->name = $name;
      $this->baseEqv = $baseEqv;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    public function getBaseEqv(){
      return $this->baseEqv;
    }
}

?>
