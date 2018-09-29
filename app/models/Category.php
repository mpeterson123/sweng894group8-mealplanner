<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Unit Class
///////////////////////////////////////////////////////////////////////////////
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Category
{
    private
        $id,
        $name;


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
}

?>
