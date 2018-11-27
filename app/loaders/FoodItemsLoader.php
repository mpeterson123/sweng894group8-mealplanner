<?php
namespace Base\Loaders;
use Base\Loaders;

class FoodItemsLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array {
        $dependencyList = array(
            'categoryRepository',
            'unitRepository',
            'foodItemFactory',
            'foodItemRepository'
        );
        $dependencies = $this->loader->loadDependencies($dependencyList);
        return $dependencies;
    }
}
