<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

abstract class Repository {
    private $dbh;

    public function __construct($dbh){
        $this->dbh = $dbh;
    }

    abstract public function find($id);
    abstract public function save($object);
    abstract public function all();
    abstract public function remove($object);
    abstract protected function insert($object);
    abstract protected function update($object);
}
