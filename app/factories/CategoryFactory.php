<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Category;

/**
 * Handles Category model instantiation
 */
class CategoryFactory {

    /**
     * Creates a new instance of Category model
     * @param  array    $categoryArray A category's properties
     * @return Category                A category object
     */
    public function make(array $categoryArray):Category
    {
        $category = new Category();
        if(isset($categoryArray['id'])){
            $category->setId(intval($categoryArray['id']));
        }
        $category->setName($categoryArray['name']);

        return $category;
    }



}
