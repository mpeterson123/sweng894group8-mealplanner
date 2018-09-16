<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Unit Class
///////////////////////////////////////////////////////////////////////////////
namespace Base\Models;

class Unit
{
    private $name;

    public function __construct($theName)
    {
        $this->name    = $theName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($theName)
    {
        $this->name   = $theName;
    }
}

?>
