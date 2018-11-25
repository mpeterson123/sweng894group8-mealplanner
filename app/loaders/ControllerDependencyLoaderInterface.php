<?php
namespace Base\Loaders;
use Base\Loaders\Loader;

interface ControllerDependencyLoaderInterface{
    public function __construct(Loader $loader);

    public function loadDependencies():array;
}
