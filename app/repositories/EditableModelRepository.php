<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

interface EditableModelRepository {

    /**
    * Insert or update an object in the database
    * @param  Base\Models\Object $object   object to be saved
    */
    public function save($object);

    /**
    * Delete an object from the database
    * @param  integer $id  Object's id
    */
    public function remove($id);

    /**
    * Insert object into the database
    * @param  Base\Models\Object $object   Object to be stored
    */
    public function insert($object);

    /**
    * Update food object in database
    * @param  Base\Models\Object $object   Object to be updated
    */
    public function update($object);


}
