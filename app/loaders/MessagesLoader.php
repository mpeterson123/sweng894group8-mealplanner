<?php
namespace Base\Loaders;
use Base\Loaders;

class MessagesLoader implements ControllerDependencyLoaderInterface {
    private $loader;

    public function __construct(Loader $loader){
        $this->loader = $loader;
    }

    public function loadDependencies():array{
        $dependencies = array();
        return $dependencies;
    }
}
