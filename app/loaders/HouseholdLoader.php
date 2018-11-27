<?php
namespace Base\Loaders;
use Base\Loaders;

class HouseholdLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array {
        $dependencyList = array(
            'householdFactory',
            'householdRepository',
            'userFactory',
            'userRepository'
        );
        $dependencies = $this->loader->loadDependencies($dependencyList);
        return $dependencies;
    }
}
