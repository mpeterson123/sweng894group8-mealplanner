<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Handles model instantiation
 */
abstract class Factory {

    /**
     * Creates a new instance of model
     * @param  array $resourceArray  An resource's properties
     */
    abstract public function make(array $resourceArray);
}
