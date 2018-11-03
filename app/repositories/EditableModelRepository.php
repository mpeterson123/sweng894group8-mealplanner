<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

interface EditableModelRepository {

    function save($object);
    function remove($object);
    function insert($object);
    function update($object);
}
