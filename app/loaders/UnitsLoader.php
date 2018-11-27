<?php
namespace Base\Loaders;

class UnitsLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array{
        $dependencyList = array(
            'unitFactory',
            'unitRepository'
        );
        $dependencies = $this->loader->loadDependencies($dependencyList);
        return $dependencies;;
    }
}
