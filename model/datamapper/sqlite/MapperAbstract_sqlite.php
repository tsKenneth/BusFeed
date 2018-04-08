<?php

require_once(__DIR__.'/../../DomainObjectAbstract.php');
require_once(__DIR__.'/../../../view/php/apiHandlerLTA.php');
require_once(__DIR__.'/../MapperAbstract.php');


class DB extends SQLite3{
  function __construct() {
    $this->open($_SERVER["DOCUMENT_ROOT"] . '/busfeed/model/datamapper/sqlite/BusFeed.db');
  }
}


abstract class MapperAbstract_sqlite extends MapperAbstract{

    // Creates a new instance of DomainObject
    public function create(array $data = null){
      $obj = $this->_create();
      if($data){
        $obj = $this->populate($obj,$data);
      }
      return $obj;
    }

    // Saves the DomainObject into Persistent Storage
    public function save(DomainObjectAbstract $obj){
      if (is_null($obj->getId())){
          $this->_insert($obj);
      }
      else{
          $this->_update($obj);
      }
    }

    // Deletes the DomainObject
    public function delete(DomainObjectAbstract $obj){
      $this->_delete($obj);
    }

    // Creates a new table for the domain object
    abstract public function createTable();
    // Pulls from API and stores teh DomainObject
    abstract public function storeFromAPI();

    // Populate the DomainObject with the values
    // to be implemented by concrete class
    abstract public function populate(DomainObjectAbstract $obj, array $data);

    // Following abstract methods will be used by this class itself
    abstract protected function _create();
    abstract protected function _insert(DomainObjectAbstract $obj);
    abstract protected function _insertMultiple(array $objArr);
    abstract protected function _update(DomainObjectAbstract $obj);
    abstract protected function _delete(DomainObjectAbstract $obj);
}

?>
