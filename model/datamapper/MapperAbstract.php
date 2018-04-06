<?php


abstract class MapperAbstract{

    abstract public function create(array $data = null);
    abstract public function save(DomainObjectAbstract $obj);
    abstract public function delete(DomainObjectAbstract $obj);
    abstract public function populate(DomainObjectAbstract $obj, array $data);

    abstract protected function _create();
    abstract protected function _insert(DomainObjectAbstract $obj);
    abstract protected function _update(DomainObjectAbstract $obj);
    abstract protected function _delete(DomainObjectAbstract $obj);
}




?>
