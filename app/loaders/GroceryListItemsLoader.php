<?php
namespace Base\Loaders;
use Base\Loaders;

class GroceryListItemsLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array {
        $dependencyList = array(
            'foodItemRepository',
            'groceryListItemFactory',
            'groceryListItemRepository'
        );
        $dependencies = $this->loader->loadDependencies($dependencyList);
        return $dependencies;
    }


}
