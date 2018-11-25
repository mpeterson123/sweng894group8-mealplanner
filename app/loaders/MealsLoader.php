<?php
namespace Base\Loaders;
use Base\Loaders;

class MealsLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array {
        $dependencyList = array(
            'foodItemRepository',
            'recipeRepository',
            'mealFactory',
            'mealRepository',
            'groceryListItemFactory',
            'groceryListItemRepository'
        );
        $dependencies = $this->loader->loadDependencies($dependencyList);
        return $dependencies;
    }
}
