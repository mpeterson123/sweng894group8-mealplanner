<?php
namespace Base\Loaders;

use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;
use Base\Helpers\Redirect;
use Base\Helpers\Format;
use \Valitron\Validator;
use Base\Helpers\Log;

use Base\Factories\CategoryFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\GroceryListItemFactory;
use Base\Factories\HouseholdFactory;
use Base\Factories\IngredientFactory;
use Base\Factories\MealFactory;
use Base\Factories\RecipeFactory;
use Base\Factories\UnitFactory;
use Base\Factories\UserFactory;

use Base\Repositories\CategoryRepository;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\GroceryListItemRepository;
use Base\Repositories\HouseholdRepository;
use Base\Repositories\IngredientRepository;
use Base\Repositories\MealRepository;
use Base\Repositories\RecipeRepository;
use Base\Repositories\UnitRepository;
use Base\Repositories\UserRepository;


class Loader {
    private $dependencies = array();

    public function __construct($db){
        $this->db = $db;
    }

    public function loadDependencies($dependencyList) {
        $dependencies = array();
        foreach ($dependencyList as $dependencyName) {
            $dependencies[$dependencyName] = call_user_func(array($this, 'load'.ucwords($dependencyName)));
        }
        return $dependencies;
    }

    /**
     * Creates CategoryFactory
     * @return CategoryFactory A CategoryFactory instance
     */
    private function loadCategoryFactory():CategoryFactory{
        //---
        if(!isset($this->dependencies['CategoryFactory'])){
            $this->dependencies['CategoryFactory'] = new CategoryFactory();
        }
        return $this->dependencies['CategoryFactory'];
        //---
    }

    /**
     * Creates FoodItemFactory
     * @return FoodItemFactory A FoodItemFactory instance
     */
    private function loadFoodItemFactory():FoodItemFactory{
        //---
        if(!isset($this->dependencies['FoodItemFactory'])){
            $this->dependencies['FoodItemFactory'] = new FoodItemFactory(
                $this->loadCategoryRepository(),
                $this->loadUnitRepository()
            );
        }
        return $this->dependencies['FoodItemFactory'];
        //---
    }

    /**
     * Creates GroceryListItemFactory
     * @return GroceryListItemFactory A GroceryListItemFactory instance
     */
    private function loadGroceryListItemFactory():GroceryListItemFactory{
        //---
        if(!isset($this->dependencies['GroceryListItemFactory'])){
            $this->dependencies['GroceryListItemFactory'] = new GroceryListItemFactory(
                $this->loadFoodItemRepository()
            );
        }
        return $this->dependencies['GroceryListItemFactory'];
        //---
    }

    /**
     * Creates HouseholdFactory
     * @return HouseholdFactory A HouseholdFactory instance
     */
    private function loadHouseholdFactory():HouseholdFactory{
        //---
        if(!isset($this->dependencies['HouseholdFactory'])){
            $this->dependencies['HouseholdFactory'] = new HouseholdFactory();
        }
        return $this->dependencies['HouseholdFactory'];
        //---
    }

    /**
     * Creates IngredientFactory
     * @return IngredientFactory A IngredientFactory instance
     */
    private function loadIngredientFactory():IngredientFactory{
        //---
        if(!isset($this->dependencies['IngredientFactory'])){
            $this->dependencies['IngredientFactory'] = new IngredientFactory(
                $this->loadFoodItemRepository(),
                $this->loadUnitRepository()
            );
        }
        return $this->dependencies['IngredientFactory'];
        //---
    }

    /**
     * Creates MealFactory
     * @return MealFactory A MealFactory instance
     */
    private function loadMealFactory():MealFactory{
        //---
        if(!isset($this->dependencies['MealFactory'])){
            $this->dependencies['MealFactory'] = new MealFactory(
                $this->loadRecipeRepository()
            );
        }
        return $this->dependencies['MealFactory'];
        //---
    }


    /**
     * Creates MessageFactory
     * @return MessageFactory A MessageFactory instance
     */
    private function loadMessageFactory():MessageFactory{
        //---
        if(!isset($this->dependencies['MessageFactory'])){
            $this->dependencies['MessageFactory'] = new MessageFactory();
        }
        return $this->dependencies['MessageFactory'];
        //---
    }

    /**
     * Creates RecipeFactory
     * @return RecipeFactory A RecipeFactory instance
     */
    private function loadRecipeFactory():RecipeFactory{
        //---
        if(!isset($this->dependencies['RecipeFactory'])){
            $this->dependencies['RecipeFactory'] = new RecipeFactory(
                $this->loadIngredientRepository()
            );
        }
        return $this->dependencies['RecipeFactory'];
        //---
    }

    /**
     * Creates UnitFactory
     * @return UnitFactory A UnitFactory instance
     */
    private function loadUnitFactory():UnitFactory{
        //---
        if(!isset($this->dependencies['UnitFactory'])){
            $this->dependencies['UnitFactory'] = new UnitFactory();
        }
        return $this->dependencies['UnitFactory'];
        //---
    }

    /**
     * Creates UserFactory
     * @return UserFactory A UserFactory instance
     */
    private function loadUserFactory():UserFactory{
        //---
        if(!isset($this->dependencies['UserFactory'])){
            $this->dependencies['UserFactory'] = new UserFactory(
                $this->loadHouseholdRepository()
            );
        }
        return $this->dependencies['UserFactory'];
        //---
    }


    ////////////////////////////////////////////////////////////////////////////
    // Repositories //
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Creates CategoryRepository
     * @return CategoryRepository A CategoryRepository instance
     */
    private function loadCategoryRepository():CategoryRepository{
        return new CategoryRepository($this->db, $this->loadCategoryFactory());
    }

    /**
     * Creates FoodItemRepository
     * @return FoodItemRepository A FoodItemRepository instance
     */
    private function loadFoodItemRepository():FoodItemRepository{
        return new FoodItemRepository($this->db, $this->loadFoodItemFactory());
    }

    /**
     * Creates GroceryListItemRepository
     * @return GroceryListItemRepository A GroceryListItemRepository instance
     */
    private function loadGroceryListItemRepository():GroceryListItemRepository{
        return new GroceryListItemRepository($this->db, $this->loadGroceryListItemFactory());
    }

    /**
     * Creates HouseholdRepository
     * @return HouseholdRepository A HouseholdRepository instance
     */
    private function loadHouseholdRepository():HouseholdRepository{
        return new HouseholdRepository($this->db, $this->loadHouseholdFactory());
    }

    /**
     * Creates IngredientRepository
     * @return IngredientRepository A IngredientRepository instance
     */
    private function loadIngredientRepository():IngredientRepository{
        return new IngredientRepository($this->db, $this->loadIngredientFactory());
    }

    /**
     * Creates MealRepository
     * @return MealRepository A MealRepository instance
     */
    private function loadMealRepository():MealRepository{
        return new MealRepository($this->db, $this->loadMealFactory());
    }

    /**
     * Creates MessageRepository
     * @return MessageRepository A MessageRepository instance
     */
    private function loadMessageRepository():MessageRepository{
        return new MessageRepository($this->db, $this->loadMessageFactory());
    }

    /**
     * Creates RecipeRepository
     * @return RecipeRepository A RecipeRepository instance
     */
    private function loadRecipeRepository():RecipeRepository{
        return new RecipeRepository($this->db, $this->loadRecipeFactory());
    }

    /**
     * Creates UnitRepository
     * @return UnitRepository A UnitRepository instance
     */
    private function loadUnitRepository():UnitRepository{
        return new UnitRepository($this->db, $this->loadUnitFactory());
    }

    /**
     * Creates UserRepository
     * @return UserRepository A UserRepository instance
     */
    private function loadUserRepository():UserRepository{
        return new UserRepository($this->db, $this->loadUserFactory());
    }
}
