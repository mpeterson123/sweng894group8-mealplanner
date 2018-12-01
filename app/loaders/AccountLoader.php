<?php
namespace Base\Loaders;
use Base\Loaders;

class AccountLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array {
        $dependencyList = array(
            'userFactory',
            'userRepository',
            'householdRepository'
        );
        $dependencies = $this->loader->loadDependencies($dependencyList);
        return $dependencies;
    }
}
