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

    /**
     * Set category id
     * @param integer $id Category id
     */
    public function setId($id):void {

        if(!$id)
        {
            throw new \Exception("Id cannot be empty", 1);
        }

        $id = intval($id);
        if($id < 1){
            throw new \Exception("Id must be greater than 0", 1);
        }

        $this->id = $id;
    }

    /**
     * Get category id
     * @return integer Category id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get category name
     * @return string Category name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set category name
     * @param string $name Category name
     */
    public function setName($name):void {
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
