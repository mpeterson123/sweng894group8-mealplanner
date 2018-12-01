<?php
namespace Base\Loaders;
use Base\Loaders;

class RecipesLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array {
        $dependencyList = array(
            'unitRepository',
            'foodItemRepository',
            'ingredientFactory',
            'ingredientRepository',
            'recipeFactory',
            'recipeRepository',
            'groceryListItemRepository',
            'groceryListItemFactory',
            'mealRepository'
        );
        $dependencies = $this->loader->loadDependencies($dependencyList);
        return $dependencies;
    }
}
