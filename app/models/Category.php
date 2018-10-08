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


    public function setId($id)
    {
        if(!$id)
        {
            throw new \Exception("Id cannot be empty", 1);
        }

        if(gettype($id) !== 'integer'){
            throw new \Exception("Id must be an integer", 1);
        }

        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Set category name
     * @param string $name Category name
     */
    public function setName($name):void{
        if($name == ''){
            throw new \Exception(
                "Category name cannot be empty", 1);
        }

        if(strlen($name) > 20){
            throw new \Exception(
                "Category name cannot be longer than 20 characters", 1);
        }

        $this->name = trim($name);
    }
}

?>
